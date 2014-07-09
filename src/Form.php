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

    $this->_getPublicProperties();
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
   * Retrieve the public properties
   *
   * @return array
   */
  protected function _getPublicProperties()
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

    $return = [];
    if(!empty(self::$_properties[$this->_calledClass]))
    {
      foreach(self::$_properties[$this->_calledClass] as $name)
      {
        $return[$name] = $this->getValue($name);
      }
    }
    return $return;
  }

  /**
   * Prepare the form
   */
  protected function _boot()
  {
    foreach($this->_getPublicProperties() as $property => $value)
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

      if($docblock->hasTag('values'))
      {
        $element->setOption(
          'values',
          ValueAs::arr($docblock->getTag('values'))
        );
      }

      $this->_elements[$property] = $element;
    }
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
