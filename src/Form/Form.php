<?php

namespace Packaged\Form\Form;

use Exception;
use Packaged\Form\DataHandlers\Interfaces\DataHandler;
use Packaged\Form\DataHandlers\ReadOnlyDataHandler;
use Packaged\Form\Decorators\DefaultDataHandlerDecorator;
use Packaged\Form\Decorators\DefaultFormDecorator;
use Packaged\Form\Decorators\FormSubmitDecorator;
use Packaged\Form\Decorators\Interfaces\DataHandlerDecorator;
use Packaged\Form\Decorators\Interfaces\Decorator;
use Packaged\Form\Decorators\Interfaces\FormDecorator;
use Packaged\Helpers\Arrays;
use Packaged\Helpers\Objects;
use Packaged\SafeHtml\ISafeHtmlProducer;
use Packaged\SafeHtml\SafeHtml;
use Packaged\Ui\Html\HtmlAttributesTrait;
use Packaged\Ui\Renderable;
use Packaged\Validate\IValidatable;
use Packaged\Validate\ValidationException;
use function array_key_exists;

abstract class Form implements Renderable, ISafeHtmlProducer, IValidatable
{
  use HtmlAttributesTrait;

  /**
   * @var DataHandler[]
   */
  protected $_dataHandlers = [];

  protected $_decorator;
  protected $_handlerDecorator;
  protected $_defaultHandlerDecorator;
  protected $_submitDecorator;

  protected $_action = '';
  protected $_method = 'post';

  public function __construct()
  {
    $this->_initDataHandlers();
    $this->_preparePublicProperties();
  }

  abstract protected function _initDataHandlers();

  final protected function _preparePublicProperties()
  {
    foreach(Objects::propertyValues($this) as $property => $value)
    {
      if($value === null)
      {
        // use read only handler if none have been specified
        $value = ReadOnlyDataHandler::i();
      }
      $this->addDataHandler($property, $value);
      //Unset the public properties to avoid data handler modification
      unset($this->$property);
    }
  }

  public function addDataHandler($property, DataHandler $handler)
  {
    $this->_dataHandlers[$property] = $handler;
    if($this->_dataHandlers[$property]->getName() === null)
    {
      $this->_dataHandlers[$property]->setName($property);
    }
    return $this;
  }

  public function getMethod()
  {
    return $this->_method;
  }

  public function setMethod(string $method)
  {
    $this->_method = $method;
    return $this;
  }

  public function getAction()
  {
    return $this->_action;
  }

  public function setAction(string $action)
  {
    $this->_action = $action;
    return $this;
  }

  public function __isset($name)
  {
    return array_key_exists($name, $this->_dataHandlers);
  }

  /**
   * @return bool
   */
  public function isValid(): bool
  {
    foreach($this->_dataHandlers as $name => $handler)
    {
      $handlerErrors = $handler->validateValue($handler->getValue(), $this->getFormData());
      if(!empty($handlerErrors))
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
    $keyedErrors = [];
    foreach($this->_dataHandlers as $name => $handler)
    {
      $handler->clearErrors();
      $handlerErrors = $handler->validateValue($handler->getValue(), $this->getFormData());
      if($handlerErrors)
      {
        $handler->addError(...$handlerErrors);
        $keyedErrors[$name] = $handlerErrors;
      }
    }
    return $keyedErrors;
  }

  /**
   * @throws Exception
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
    $keyedHandlers = Objects::mpull($this->_dataHandlers, null, 'getName');

    $mergedFormData = array_merge($this->getFormData(), $data);
    $keyedErrors = [];
    foreach($data as $name => $value)
    {
      $ele = Arrays::value($keyedHandlers, $name);
      if($ele instanceof DataHandler)
      {
        $value = $ele->formatValue($value);
        $handlerErrors = $ele->validateValue($value, $mergedFormData);
        if(empty($handlerErrors))
        {
          $ele->setValue($value);
        }
        else
        {
          $keyedErrors[$name] = $handlerErrors;
          if($hydrateInvalidValues)
          {
            $ele->setValue($value);
          }
        }
      }
    }
    return $keyedErrors;
  }

  public function __get($name)
  {
    return Arrays::value($this->_dataHandlers, $name);
  }

  public function __set($name, $value)
  {
    if(isset($this->_dataHandlers[$name]))
    {
      $this->_dataHandlers[$name]->setValue($value);
    }
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

  public function getSubmitDecorator(): ?Decorator
  {
    if(!$this->_submitDecorator)
    {
      $this->_submitDecorator = $this->_defaultSubmitDecorator();
    }
    return $this->_submitDecorator;
  }

  protected function _defaultSubmitDecorator(): Decorator
  {
    return new FormSubmitDecorator();
  }

  public function render(): string
  {
    return $this->produceSafeHTML()->getContent();
  }

  public function produceSafeHTML(): SafeHtml
  {
    return $this->getDecorator()->produceSafeHTML();
  }

  public function getDecorator(): FormDecorator
  {
    if(!$this->_decorator)
    {
      $this->_decorator = $this->_defaultDecorator();
    }
    return $this->_decorator->setForm($this);
  }

  public function setDecorator(FormDecorator $decorator)
  {
    $this->_decorator = $decorator;
    return $this;
  }

  protected function _defaultDecorator(): FormDecorator
  {
    return new DefaultFormDecorator();
  }

  /**
   * @param $for
   *
   * @return Decorator|FormDecorator|DataHandlerDecorator
   */
  public function decorate($for): Decorator
  {
    if($for instanceof Form)
    {
      return $for->getDecorator();
    }
    else if($for instanceof DataHandler)
    {
      $decorator = $this->getHandlerDecorator($for->getName());
      $decorator->setHandler($for);
      return $decorator;
    }
    else if(is_string($for) && isset($this->_dataHandlers[$for]))
    {
      $decorator = $this->getHandlerDecorator($for);
      $decorator->setHandler($this->_dataHandlers[$for]);
      return $decorator;
    }
    return $this->getDecorator();
  }

  public function getHandlerDecorator($fieldName = null): DataHandlerDecorator
  {
    if(!isset($this->_handlerDecorator[$fieldName]))
    {
      $this->_handlerDecorator[$fieldName] = clone $this->_defaultHandlerDecorator();
    }
    return $this->_handlerDecorator[$fieldName];
  }

  public function setHandlerDecorator(DataHandlerDecorator $decorator, string $field = null)
  {
    if($field === null)
    {
      $this->_defaultHandlerDecorator = $decorator;
    }
    else
    {
      $this->_handlerDecorator[$field] = $decorator;
    }
    return $this;
  }

  protected function _defaultHandlerDecorator(): DataHandlerDecorator
  {
    if($this->_defaultHandlerDecorator === null)
    {
      $this->_defaultHandlerDecorator = new DefaultDataHandlerDecorator();
    }
    return $this->_defaultHandlerDecorator;
  }

}
