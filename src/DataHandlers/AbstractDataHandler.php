<?php
namespace PackagedUi\Form\DataHandlers;

use PackagedUi\Form\DataHandlers\Interfaces\DataHandler;
use PackagedUi\Form\Decorators\InputDecorator;
use PackagedUi\Form\Decorators\Interfaces\DataHandlerDecorator;

abstract class AbstractDataHandler implements DataHandler
{
  protected $_name;
  protected $_value;
  /** @var DataHandlerDecorator */
  protected $_decorator;

  protected $_label;
  protected $_placeholder;
  protected $_defaultValue;

  /**
   * @return string
   */
  public function getName(): ?string
  {
    return $this->_name;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function setName($name)
  {
    $this->_name = $name;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getValue()
  {
    return $this->_value;
  }

  /**
   * @param mixed $value
   *
   * @return DataHandler
   */
  public function setValue($value)
  {
    $this->_value = $value;
    return $this;
  }

  /**
   * @param mixed $value
   *
   * @return DataHandler
   * @throws \Exception
   */
  public function setValueFormatted($value)
  {
    $this->_value = $this->formatValue($value);
    return $this;
  }

  /**
   * Validate a value against the current data handler
   *
   * @param $value
   *
   * @return bool
   */
  public function isValidValue($value): bool
  {
    try
    {
      $this->validateValue($value);
    }
    catch(\Exception $e)
    {
      return false;
    }
    return true;
  }

  /**
   * Validate the currently set value
   *
   * @return bool
   */
  public function isValid(): bool
  {
    return $this->isValidValue($this->getValue());
  }

  public function validate()
  {
    $this->validateValue($this->getValue());
  }

  public function validateValue($value)
  {
  }

  public function formatValue($value)
  {
    return $value;
  }

  /**
   * @param DataHandlerDecorator $decorator
   *
   * @return $this
   */
  public function setDecorator(DataHandlerDecorator $decorator)
  {
    $this->_decorator = $decorator;
    return $this;
  }

  public function getDecorator(): DataHandlerDecorator
  {
    if(!$this->_decorator)
    {
      $this->_decorator = $this->_defaultDecorator();
    }
    return $this->_decorator->setHandler($this);
  }

  protected function _defaultDecorator(): DataHandlerDecorator
  {
    return new InputDecorator();
  }

  /**
   * @return mixed
   */
  public function getLabel()
  {
    return $this->_label;
  }

  /**
   * @param mixed $label
   *
   * @return AbstractDataHandler
   */
  public function setLabel($label)
  {
    $this->_label = $label;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getPlaceholder()
  {
    return $this->_placeholder;
  }

  /**
   * @param mixed $placeholder
   *
   * @return AbstractDataHandler
   */
  public function setPlaceholder($placeholder)
  {
    $this->_placeholder = $placeholder;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getDefaultValue()
  {
    return $this->_defaultValue;
  }

  /**
   * @param mixed $defaultValue
   *
   * @return AbstractDataHandler
   */
  public function setDefaultValue($defaultValue)
  {
    $this->_defaultValue = $defaultValue;
    return $this;
  }

}
