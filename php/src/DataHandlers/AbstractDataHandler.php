<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Form\DataHandlers\Interfaces\DataHandler;
use Packaged\Helpers\Objects;
use Packaged\Helpers\Strings;
use Packaged\SafeHtml\ISafeHtmlProducer;
use Packaged\Ui\Html\HtmlElement;
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
  protected $_guidance;

  protected $_errors = [];
  /**
   * @var IValidator[]
   */
  protected $_validators = [];
  private $_isValidatorSetUp = false;

  /**
   * @var HtmlElement
   */
  protected $_input;

  public static function i()
  {
    if(func_num_args() > 0)
    {
      return new static(...func_get_args());
    }
    return new static();
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
  public function setName(string $name)
  {
    $this->_name = $name;
    return $this;
  }

  //region Presentation

  /**
   * @return string|null
   */
  public function getId(): ?string
  {
    if(empty($this->_id) && $this->_name)
    {
      // create an id
      $this->_id = strtolower(
        str_replace(' ', '-', Strings::splitOnCamelCase($this->_name))
        . '-' . base_convert(floor(microtime(true) * 1000), 10, 36)
      );
    }

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
   * @param string|ISafeHtmlProducer $label
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
    return $this->_placeholder ?? Strings::titleize(Strings::splitOnCamelCase($this->getName()));
  }

  /**
   * @param string $placeholder
   *
   * @return AbstractDataHandler
   */
  public function setPlaceholder(string $placeholder)
  {
    $this->_placeholder = $placeholder;
    return $this;
  }

  /**
   * @return string|ISafeHtmlProducer
   */
  public function getGuidance()
  {
    return $this->_guidance;
  }

  /**
   * @param string|ISafeHtmlProducer $guidance
   *
   * @return AbstractDataHandler
   */
  public function setGuidance($guidance)
  {
    $this->_guidance = $guidance;
    return $this;
  }

  //endregion

  //region Value Handling

  public function formatValue($value)
  {
    return $value;
  }

  /**
   * @param mixed $value
   * @param bool  $formatValue
   *
   * @return $this
   */
  public function setValue($value, $formatValue = true)
  {
    $this->_value = $formatValue ? $this->formatValue($value) : $value;
    return $this;
  }

  public function getValue($default = null, $withDefault = true, $formatValue = true)
  {
    $value = $withDefault ? ($this->_value ?? ($default ?? $this->getDefaultValue())) : $this->_value;
    return $formatValue ? $this->formatValue($value) : $value;
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
  //endregion

  //region Validators
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

  public function clearValidators($restoreDefaults = true)
  {
    $this->_validators = [];
    if($restoreDefaults)
    {
      $this->_setupValidators();
    }
    return $this;
  }

  protected function _setupValidators()
  {
  }

  private function _initValidators()
  {
    if(!$this->_isValidatorSetUp)
    {
      $this->_isValidatorSetUp = true;
      $this->_setupValidators();
    }
  }

  public function getValidators()
  {
    $this->_initValidators();
    return $this->_validators;
  }

  public function getErrors(): array
  {
    return $this->_errors;
  }

  public function addError(ValidationException ...$errors)
  {
    $this->_errors = array_merge($this->_errors, $errors);
    return $this;
  }

  public function clearErrors()
  {
    $this->_errors = [];
    return $this;
  }

  public function validate(): array
  {
    $this->clearErrors();
    $errors = $this->validateValue($this->getValue());
    $this->addError(...$errors);
    return $this->getErrors();
  }

  /**
   * Validate the data, throwing an exception with the error
   *
   * @param mixed $value
   * @param array $data
   *
   * @return ValidationException[]
   * @throws RuntimeException
   */
  public function validateValue($value, array $data = []): array
  {
    $this->_initValidators();
    $errors = [];
    if($this->_validators)
    {
      foreach($this->_validators as $validator)
      {
        if($validator instanceof IDataSetValidator)
        {
          if(!$data)
          {
            throw new RuntimeException('no form provided to dataset validator');
          }
          $validator->setData($data);
        }
        $validatorErrors = $validator->validate($value);

        $errors = array_merge($errors, $validatorErrors);
      }
    }
    return $errors;
  }

  /**
   * @throws ValidationException
   */
  public function assert()
  {
    self::assertValue($this->getValue());
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
    $this->_initValidators();
    if($this->_validators)
    {
      foreach($this->_validators as $validator)
      {
        $validator->assert($value);
      }
    }
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
    $this->_initValidators();
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

  //endregion

  abstract protected function _generateInput(): HtmlElement;

  public function getInput(): HtmlElement
  {
    if($this->_input === null)
    {
      $this->_input = $this->_generateInput();
      if(is_callable($this->_postInputMutator))
      {
        $func = $this->_postInputMutator;
        $this->_input = $func($this->_input);
      }
    }
    return $this->_input;
  }

  protected $_postInputMutator;

  /**
   * Allow modification of the input after its generator has executed
   *
   * @param callable $func func(HtmlElement): HtmlElement
   *
   * @return $this
   */
  public function mutateInput(callable $func)
  {
    $this->_postInputMutator = $func;
    return $this;
  }

  public function getInputClass(): string
  {
    return Strings::urlize(
      Strings::splitOnCamelCase(str_replace('DataHandler', '', Objects::classShortname($this)))
    );
  }

  public function wrapInput(HtmlElement $input): HtmlElement
  {
    return $input;
  }
}
