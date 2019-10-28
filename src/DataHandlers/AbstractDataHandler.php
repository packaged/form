<?php
namespace Packaged\Form\DataHandlers;

use Exception;
use Packaged\Form\DataHandlers\Interfaces\DataHandler;
use Packaged\Form\Decorators\Interfaces\DataHandlerDecorator;
use Packaged\Validate\IValidator;
use Packaged\Validate\ValidationException;
use function array_merge;

abstract class AbstractDataHandler implements DataHandler
{
  protected $_name;
  protected $_value;
  protected $_label;
  protected $_placeholder;
  protected $_defaultValue;

  /** @var DataHandlerDecorator */
  protected $_decorator;
  protected $_errors = [];
  /**
   * @var IValidator[]
   */
  protected $_validators = [];
  private $_isValidatorSetUp = false;

  public function addError(ValidationException ...$errors)
  {
    $this->_errors = array_merge($this->_errors, $errors);
    return $this;
  }

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
   * @param mixed $value
   *
   * @return DataHandler
   * @throws Exception
   */
  public function setValueFormatted($value)
  {
    $this->_value = $this->formatValue($value);
    return $this;
  }

  public function formatValue($value)
  {
    return $value;
  }

  /**
   * @param IValidator $validator
   *
   * @return $this
   */
  public function addValidator(IValidator $validator)
  {
    $this->_validators[] = $validator;
    return $this;
  }

  public function clearValidators()
  {
    $this->_validators = [];
    $this->_setupValidator();
    return $this;
  }

  protected function _setupValidator()
  {
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

  /**
   * Validate a value against the current data handler
   *
   * @param $value
   *
   * @return bool
   */
  public function isValidValue($value): bool
  {
    $this->_initValidator();
    if($this->_validators)
    {
      foreach($this->_validators as $validator)
      {
        if(!$validator->isValid($value))
        {
          return false;
        }
      }
    }
    return true;
  }

  private function _initValidator()
  {
    if(!$this->_isValidatorSetUp)
    {
      $this->_isValidatorSetUp = true;
      $this->_setupValidator();
    }
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

  public function validate(): array
  {
    $this->clearErrors()->addError(...$this->validateValue($this->getValue()));
    return $this->getErrors();
  }

  public function clearErrors()
  {
    $this->_errors = [];
    return $this;
  }

  /**
   * Validate the data, throwing an exception with the error
   *
   * @param $value
   *
   * @return ValidationException[]
   */
  public function validateValue($value): array
  {
    $this->_initValidator();
    $errors = [];
    if($this->_validators)
    {
      foreach($this->_validators as $validator)
      {
        $errors = array_merge($errors, $validator->validate($value));
      }
    }
    return $errors;
  }

  public function getErrors(): array
  {
    return $this->_errors;
  }

  /**
   * @throws ValidationException
   */
  public function assert()
  {
    $this->assertValue($this->getValue());
  }

  /**
   * Validate the data, throwing an exception with the error
   *
   * @param $value
   *
   * @throws ValidationException
   */
  public function assertValue($value)
  {
    $this->_initValidator();
    if($this->_validators)
    {
      foreach($this->_validators as $validator)
      {
        $validator->assert($value);
      }
    }
  }

  public function getDecorator(): DataHandlerDecorator
  {
    if(!$this->_decorator)
    {
      $this->_decorator = $this->_defaultDecorator();
    }
    return $this->_decorator->setHandler($this);
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

  abstract protected function _defaultDecorator(): DataHandlerDecorator;

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

  public function render(): string
  {
    return $this->getDecorator()->render();
  }

  public function __toString()
  {
    return $this->render();
  }

}
