<?php
namespace PackagedUi\Form\Form;

use Packaged\Helpers\Arrays;
use Packaged\Helpers\Objects;
use Packaged\SafeHtml\ISafeHtmlProducer;
use Packaged\SafeHtml\SafeHtml;
use Packaged\Ui\Renderable;
use Packaged\Validate\IValidatable;
use Packaged\Validate\ValidationException;
use PackagedUi\Form\DataHandlers\Interfaces\DataHandler;
use PackagedUi\Form\Decorators\DefaultFormDecorator;
use PackagedUi\Form\Form\Interfaces\FormDecorator;

abstract class Form implements Renderable, ISafeHtmlProducer, IValidatable
{
  /**
   * @var DataHandler[]
   */
  protected $_dataHandlers = [];

  protected $_errors;

  protected $_decorator;

  public function __construct()
  {
    $this->_initDataHandlers();
    $this->_preparePublicProperties();
  }

  public function getMethod()
  {
    return 'POST';
  }

  public function getAction()
  {
    return '';
  }

  final protected function _preparePublicProperties()
  {
    foreach(Objects::propertyValues($this) as $property => $value)
    {
      $this->_dataHandlers[$property] = $value;
      $this->_dataHandlers[$property]->setName($property);
      //Unset the public properties to avoid data handler modification
      unset($this->$property);
    }
  }

  public function __set($name, $value)
  {
    if(isset($this->_dataHandlers[$name]))
    {
      $this->_dataHandlers[$name]->setValue($value);
    }
  }

  public function __isset($name)
  {
    return array_key_exists($name, $this->_dataHandlers);
  }

  public function __get($name)
  {
    return Arrays::value($this->_dataHandlers, $name);
  }

  abstract protected function _initDataHandlers();

  /**
   * @return bool
   */
  public function isValid(): bool
  {
    foreach($this->_dataHandlers as $name => $handler)
    {
      if(!$handler->isValid())
      {
        return false;
      }
    }
    return true;
  }

  /**
   * @return ValidationException[][]
   */
  public function validate(): array
  {
    $errors = [];
    foreach($this->_dataHandlers as $name => $handler)
    {
      $handlerErrors = $handler->validate();
      if($handlerErrors)
      {
        $handler->clearErrors()->addError(...$handlerErrors);
        if(!isset($errors[$name]))
        {
          $errors[$name] = [];
        }
        $errors[$name] = array_merge($errors[$name], $handlerErrors);
      }
    }
    return $errors;
  }

  /**
   * @throws \Exception
   */
  public function assert()
  {
    foreach($this->_dataHandlers as $name => $handler)
    {
      $handler->assert();
    }
  }

  /**
   * @param array $data
   *
   * @param bool  $hydrateInvalidValues
   *
   * @return array Keys in the data that do not have valid values
   */
  public function hydrate(array $data, $hydrateInvalidValues = false)
  {
    $errorKeys = [];
    foreach($data as $name => $value)
    {
      $ele = $this->__get($name);
      if($ele instanceof DataHandler)
      {
        $value = $ele->formatValue($value);
        $handlerErrors = $ele->validateValue($value);
        if(empty($handlerErrors))
        {
          $ele->setValue($value);
        }
        else
        {
          $errorKeys[$name] = $handlerErrors;
          if($hydrateInvalidValues)
          {
            $ele->setValue($value);
          }
        }
      }
    }
    return $errorKeys;
  }

  public function getFormData()
  {
    $data = [];
    foreach($this->_dataHandlers as $name => $handler)
    {
      $data[$name] = $handler->getValue();
    }
    return $data;
  }

  /**
   * @return DataHandler[]
   */
  public function getDataHandlers()
  {
    return $this->_dataHandlers;
  }

  public function getDecorator(): FormDecorator
  {
    if(!$this->_decorator)
    {
      $this->_decorator = $this->_defaultDecorator();
    }
    return $this->_decorator->setForm($this);
  }

  protected function _defaultDecorator(): FormDecorator
  {
    return new DefaultFormDecorator();
  }

  public function produceSafeHTML(): SafeHtml
  {
    return $this->getDecorator()->produceSafeHTML();
  }

  public function render(): string
  {
    return (string)$this->produceSafeHTML();
  }
}
