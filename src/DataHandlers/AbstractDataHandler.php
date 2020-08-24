<?php
namespace Packaged\Form\DataHandlers;

use Exception;
use Packaged\Form\DataHandlers\Interfaces\DataHandler;
use Packaged\Form\Decorators\AbstractDataHandlerDecorator;
use Packaged\Form\Decorators\Interfaces\DataHandlerDecorator;
use Packaged\Form\Form\Form;
use Packaged\Helpers\Strings;
use Packaged\SafeHtml\ISafeHtmlProducer;
use Packaged\Validate\IDataSetValidator;
use Packaged\Validate\IValidator;
use Packaged\Validate\ValidationException;
use RuntimeException;
use function array_merge;

abstract class AbstractDataHandler implements DataHandler
{
  protected $_id;
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

  public static function i()
  {
    if(func_num_args() > 0)
    {
      return new static(...func_get_args());
    }
    return new static();
  }

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
   * @return $this
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
   * @return $this
   */
  public function setValue($value)
  {
    $this->_value = $value;
    return $this;
  }

  public function validate(): array
  {
    $this->validateValue($this->getValue());
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
   * @param mixed     $value
   * @param Form|null $form
   *
   * @return ValidationException[]
   * @throws RuntimeException
   */
  public function validateValue($value, ?Form $form = null): array
  {
    $this->_initValidator();
    $errors = [];
    if($this->_validators)
    {
      foreach($this->_validators as $validator)
      {
        if($validator instanceof IDataSetValidator)
        {
          if(!$form)
          {
            throw new RuntimeException('no form provided to dataset validator');
          }
          $validatorErrors = $validator->validate($form->getFormData());
        }
        else
        {
          $validatorErrors = $validator->validate($value);
        }

        $errors = array_merge($errors, $validatorErrors);
      }
    }
    $this->clearErrors()->addError(...$errors);
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
      $this->_decorator->setHandler($this);
    }
    return $this->_decorator;
  }

  /**
   * @param DataHandlerDecorator $decorator
   *
   * @return $this
   */
  public function setDecorator(DataHandlerDecorator $decorator)
  {
    $decorator->setHandler($this);
    $this->_decorator = $decorator;
    return $this;
  }

  abstract protected function _defaultDecorator(): DataHandlerDecorator;

  /**
   * @return string|null
   */
  public function getId(): ?string
  {
    return $this->_id;
  }

  /**
   * @param string $id
   *
   * @return AbstractDataHandler
   */
  public function setId(string $id)
  {
    $this->_id = $id;
    return $this;
  }

  /**
   * @return string
   */
  public function getLabel()
  {
    return $this->_label ?? Strings::titleize(Strings::splitOnCamelCase($this->getName()));
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

  public function renderInput(): string
  {
    return $this->_renderElement(DataHandlerDecorator::INPUT);
  }

  public function renderLabel(): string
  {
    return $this->_renderElement(DataHandlerDecorator::LABEL);
  }

  public function renderErrors(): string
  {
    return $this->_renderElement(DataHandlerDecorator::ERRORS);
  }

  protected function _renderElement($type): string
  {
    $dec = $this->getDecorator();
    return $dec instanceof AbstractDataHandlerDecorator ? (string)$dec->getElement($type) : '';
  }

  public function __toString()
  {
    return $this->render();
  }

  public function getInput(): ?ISafeHtmlProducer
  {
    $dec = $this->getDecorator();
    $dec->render();
    return $dec->getInput();
  }
}
