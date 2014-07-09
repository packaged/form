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

      $element = new FormElement($this, $property, $type, $label);

      $this->_elements[$property] = $this->_processDocBlock(
        $docblock,
        $element
      );
    }
  }

  protected function _processDocBlock(DocBlockParser $doc, FormElement $element)
  {
    foreach($doc->getTags() as $tag => $values)
    {
      foreach($values as $value)
      {
        switch($tag)
        {
          case 'label':
          case 'inputType':
          case 'type':
          case 'input':
            break;
          case 'values':
            $element->setOption('values', ValueAs::arr($value));
            break;
          case 'novalidate':
          case 'multiple':
          case 'autofocus':
          case 'required':
            $element->setAttribute($tag, null);
            break;
          case 'disabled':
            $element->setAttribute('disabled', true);
            break;
          default:
            $element->setAttribute($tag, $value);
            break;
        }
      }
    }

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
    //Only set values for available public properties
    if(in_array($property, static::$_properties[$this->_calledClass]))
    {
      $this->getDataObject()->$property = $value;
    }
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

  public function setRenderer(IFormRenderer $renderer)
  {
    $this->_renderer = $renderer;
    return $this;
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
