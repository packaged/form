<?php
namespace PackagedUi\Form;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Form\AbstractFormElementTag;
use Packaged\Helpers\Arrays;
use Packaged\Helpers\Objects;

abstract class Form extends HtmlTag
{
  protected $_tag = 'form';
  /**
   * @var DataHandler[]
   */
  protected $_dataHandlers = [];

  protected $_errors;

  public function __construct()
  {
    parent::__construct();
    $this->_initDataHandlers();
    $this->setAttribute('method', $this->_getMethod());
    if($this->_getAction())
    {
      $this->setAttribute('action', $this->_getAction());
    }
    $this->_preparePublicProperties();
  }

  protected function _getMethod()
  {
    return 'POST';
  }

  protected function _getAction()
  {
    return '';
  }

  final protected function _preparePublicProperties()
  {
    foreach(Objects::propertyValues($this) as $property => $value)
    {
      $this->_dataHandlers[$property] = $value;
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

  public function isValid(): bool
  {
    $this->validate(false);
    return empty($this->_errors);
  }

  public function validate($throw = true)
  {
    $this->_errors = [];
    foreach($this->_dataHandlers as $name => $handler)
    {
      try
      {
        $handler->validate($handler->getValue());
      }
      catch(\Exception $e)
      {
        if($throw)
        {
          throw $e;
        }
        $this->_errors[$name] = $e->getMessage();
      }
    }
    return $this->_errors;
  }

  public function getErrors()
  {
    return $this->_errors ?? $this->validate(false);
  }

  /**
   * @param array $data
   *
   * @return array Keys in the data that do not have valid values
   */
  public function hydrate(array $data)
  {
    $errorKeys = [];
    foreach($data as $name => $value)
    {
      $ele = $this->__get($name);
      if($ele instanceof DataHandler)
      {
        try
        {
          $value = $ele->formatValue($value);
          $ele->validate($value);
          $ele->setValue($value);
        }
        catch(\Exception $e)
        {
          $errorKeys[$name] = $e->getMessage();
        }
      }
    }
    return $errorKeys;
  }

  protected function _getContentForRender()
  {
    $result = [];
    foreach($this->_dataHandlers as $name => $handler)
    {
      $ele = $handler->getDecorator()->buildElement($handler);
      if($ele instanceof AbstractFormElementTag)
      {
        $ele->setName($name);
      }
      $result[] = $ele;
    }
    return $result;
  }
}
