<?php
namespace Packaged\Form;

use Packaged\DocBlock\DocBlockParser;
use Packaged\Form\Render\FormRenderer;
use Packaged\Form\Render\IFormRenderer;
use Packaged\Helpers\Strings;
use Packaged\Helpers\ValueAs;

class Form
{
  use OptionsTrait;

  protected $_calledClass;
  protected $_elements;

  protected $_id;
  protected $_dataHolder;
  protected $_aliases;
  protected $_showAutoSubmitButton = true;
  private $_sessionId;
  protected $_csrfField = 'frsctoken';
  public $frsctoken;
  protected $_newToken;

  /**
   * @var DocBlockParser[][]
   */
  protected static $_propDocBlocks = [];
  protected static $_properties = [];

  /**
   * @var IFormRenderer
   */
  protected $_renderer;

  public function __construct(
    $action = null, $method = 'post', $name = null, $disableStartup = false
  )
  {
    if($name === null)
    {
      $name = 'Form-' . Strings::randomString(4);
    }
    $this->_options['action'] = $action;
    $this->_options['method'] = $method;
    $this->_options['name'] = $name;
    $this->_id = Strings::urlize($name);

    if(!$disableStartup)
    {
      $this->_startup();
    }
  }

  final protected function _startup()
  {
    if($this->_calledClass === null)
    {
      $this->_calledClass = get_called_class();
    }

    //Run form specific bootups
    $this->_boot();

    //Configure the form
    $this->_configure();

    $this->_processPublicProperties();
  }

  /**
   * @return mixed
   */
  public function getSessionId()
  {
    return $this->_sessionId;
  }

  /**
   * @param mixed $sessionId
   */
  public function setSessionId($sessionId)
  {
    $this->_sessionId = $sessionId;
  }

  protected function _buildCsrf()
  {
    if(empty($this->_sessionId))
    {
      $this->_sessionId = session_id();
    }

    $token = $this->getCsrfToken();
    $this->_newToken = $token;
    $this->setValue($this->_csrfField, $token);
    $element = new FormElement(
      $this,
      $this->_csrfField,
      FormElement::HIDDEN,
      null,
      FormElement::LABEL_NONE
    );
    $element->setDataObject($this, $this->_csrfField);

    $this->_elements[$this->_csrfField] = $element;
  }

  public function getCsrfToken()
  {
    return $this->_getCsrfKey() . password_hash(
      $this->_getCsrfRaw($this->_getCsrfKey(), time()),
      PASSWORD_DEFAULT
    );
  }

  protected function _getCsrfKey()
  {
    return substr(md5($this->_id), 6, 6);
  }

  protected function _getCsrfRaw($key, $time = null)
  {
    if($time === null)
    {
      $time = time();
    }
    return $this->_sessionId . $key . date("YmdH", $time);
  }

  public function isValidCsrf()
  {
    return $this->verifyCsrfToken($this->getValue($this->_csrfField));
  }

  public function verifyCsrfToken($token)
  {
    if(empty($token) || $token == $this->_newToken)
    {
      return false;
    }

    $key = substr($token, 0, 6);
    $token = substr($token, 6);
    $pass = password_verify($this->_getCsrfRaw($key), $token);

    if(!$pass)
    {
      $pass = password_verify($this->_getCsrfRaw($key, time() - 3600), $token);
    }

    return (bool)$pass;
  }

  public static function fromClass(
    $class, $action = null, $method = 'post', $name = null
  )
  {
    $form = new self($action, $method, $name, true);
    $form->_dataHolder = $class;
    $form->_calledClass = get_class($class);
    $form->_startup();
    return $form;
  }

  /**
   * Process the data object
   *
   * @return array
   */
  protected function _processPublicProperties()
  {
    if(!isset(self::$_properties[$this->_calledClass]))
    {
      static::$_properties[$this->_calledClass] = [];

      $reflect = new \ReflectionClass($this->_calledClass);
      foreach($reflect->getProperties(\ReflectionProperty::IS_PUBLIC) as $pub)
      {
        if($pub->isStatic())
        {
          continue;
        }
        static::$_properties[$this->_calledClass][] = $pub->getName();
      }
    }

    return static::$_properties[$this->_calledClass];
  }

  /**
   * Prepare the form
   */
  protected function _boot()
  {
    $formDoc = DocBlockParser::fromObject($this->getDataObject());
    $labelPosition = FormElement::LABEL_BEFORE;
    $defaultTags = [];

    foreach($formDoc->getTags() as $tag => $values)
    {
      foreach($values as $value)
      {
        if(Strings::startsWith($tag, 'element', false))
        {
          $defaultTags[substr($tag, 7)] = $value;
        }
      }
    }

    foreach($this->_processPublicProperties() as $property)
    {
      if(isset($this->_elements[$property]))
      {
        continue;
      }

      static::$_propDocBlocks[$this->_calledClass][$property] = $docblock
        = DocBlockParser::fromProperty($this->getDataObject(), $property);

      //Setup the type
      $type = $docblock->getTagFailover(["inputType", "type", "input"]);
      if($type === null)
      {
        $type = FormElement::calculateType($property);
      }

      //Setup the label
      $label = $docblock->getTag("label");
      if($label === null)
      {
        $label = Strings::humanize($property);
      }

      $element = new FormElement(
        $this,
        $property,
        $type,
        $label,
        $labelPosition
      );

      foreach($defaultTags as $tag => $value)
      {
        $element->processDocBlockTag($tag, $value);
      }

      $element->setDataObject($this->getDataObject(), $property);
      $this->_aliases[$element->getName()] = $property;

      foreach($docblock->getTags() as $tag => $values)
      {
        foreach($values as $value)
        {
          $element->processDocBlockTag($tag, $value);
        }
      }

      $this->_elements[$property] = $element;
    }

    $this->_buildCsrf();
  }

  protected function _processDocBlock(DocBlockParser $doc, FormElement $element)
  {

    return $element;
  }

  public function getDataObject()
  {
    return $this->_dataHolder !== null ? $this->_dataHolder : $this;
  }

  /**
   * Final class mapper configuration
   */
  protected function _configure()
  {
  }

  public function getId()
  {
    return $this->_id;
  }

  /**
   * Set the value of multiple properties
   *
   * @param array $data
   *
   * @return $this
   */
  public function hydrate(array $data)
  {
    foreach($data as $key => $value)
    {
      $this->setValue($key, $value);
    }
    return $this;
  }

  /**
   * Get the value of a property
   *
   * @param $property
   * @param $default
   *
   * @return mixed
   */
  public function getValue($property, $default = null)
  {
    $ret = $this->getDataObject()->$property;
    if($ret === null)
    {
      return $default;
    }
    return $ret;
  }

  /**
   * Set the value of a property
   *
   * @param $property
   * @param $value
   *
   * @return $this
   */
  public function setValue($property, $value)
  {
    if(isset($this->_aliases[$property]))
    {
      $property = $this->_aliases[$property];
    }

    //Only set values for available public properties
    if(in_array($property, static::$_properties[$this->_calledClass]))
    {
      if(isset(static::$_propDocBlocks[$this->_calledClass][$property]))
      {
        $docblock = static::$_propDocBlocks[$this->_calledClass][$property];
        if($docblock->hasTag('nullify') && $value === '')
        {
          $value = null;
        }
      }
      $this->getDataObject()->$property = $value;
    }
    return $this;
  }

  public function addPropertyAlias($alias, $property)
  {
    $this->_aliases[$alias] = $property;
    return $this;
  }

  /**
   * @return FormElement[] | null
   */
  public function getElements()
  {
    return $this->_elements;
  }

  /**
   * @param $element
   *
   * @return FormElement
   * @throws \Exception
   */
  public function getElement($element)
  {
    if(!isset($this->_elements[$element]))
    {
      throw new \Exception("The element '$element' does not exist");
    }
    return $this->_elements[$element];
  }

  /**
   * Check to see if an element exists
   *
   * @param $element
   *
   * @return bool
   */
  public function hasElement($element)
  {
    return isset($this->_elements[$element]);
  }

  public function setRenderer(IFormRenderer $renderer)
  {
    $this->_renderer = $renderer;
    return $this;
  }

  public function getValues()
  {
    $result = [];
    foreach($this->_elements as $element)
    {
      /**
       * @var $element FormElement
       */
      $result[$element->getName()] = $element->getValue();
    }
    return $result;
  }

  /**
   * @param bool $bool
   *
   * @return $this
   */
  public function showAutoSubmitButton($bool)
  {
    $this->_showAutoSubmitButton = ValueAs::bool($bool);
    return $this;
  }

  public function render()
  {
    if($this->_renderer === null)
    {
      $this->_renderer = new FormRenderer(null, $this->_showAutoSubmitButton);
    }

    return $this->_renderer->render($this);
  }

  public function __toString()
  {
    return $this->render();
  }
}
