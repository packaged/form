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
  protected static $_properties;
  protected $_elements;

  protected $_id;
  protected $_dataHolder;
  protected $_aliases;

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
    $this->_options['name']   = $name;
    $this->_id                = Strings::urlize($name);

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

  public static function fromClass(
    $class, $action = null, $method = 'post', $name = null
  )
  {
    $form               = new self($action, $method, $name, true);
    $form->_dataHolder  = $class;
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
    $formDoc       = DocBlockParser::fromObject($this->getDataObject());
    $labelPosition = FormElement::LABEL_BEFORE;
    $defaultTags   = [];

    foreach($formDoc->getTags() as $tag => $values)
    {
      foreach($values as $value)
      {
        if(starts_with($tag, 'element'))
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

      $docblock = DocBlockParser::fromProperty(
        $this->getDataObject(),
        $property
      );

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
   *
   * @return mixed
   */
  public function getValue($property)
  {
    return $this->getDataObject()->$property;
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

  public function render()
  {
    if($this->_renderer === null)
    {
      $this->_renderer = new FormRenderer();
    }

    return $this->_renderer->render($this);
  }

  public function __toString()
  {
    return $this->render();
  }
}
