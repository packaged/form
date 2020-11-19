<?php
namespace Packaged\Form\DataHandlers\Interfaces;

use Exception;
use Packaged\Ui\Html\HtmlElement;
use Packaged\Validate\IValidatable;
use Packaged\Validate\ValidationException;

interface DataHandler extends IValidatable
{
  public function getId(): ?string;

  public function setId(string $id);

  public function getName(): ?string;

  /**
   * @param string $name
   *
   * @return DataHandler
   */
  public function setName(string $name);

  /**
   * Format a value into a safe value for the handler, throwing an exception if not compatible
   *
   * @param mixed $value the value to be formatted
   *
   * @return mixed the formatted value
   */
  public function formatValue($value);

  /**
   * @param mixed $value
   *
   * @return DataHandler
   */
  public function setValue($value);

  public function getValue();

  public function getValueWithDefault($default = null);

  public function getDefaultValue();

  /**
   * @param string $placeholder
   *
   * @return DataHandler
   */
  public function setPlaceholder(string $placeholder);

  public function getPlaceholder();

  /**
   * @param string $label
   *
   * @return DataHandler
   */
  public function setLabel(string $label);

  public function getLabel();


  //Validation

  /**
   * Validate the data, return true if valid, false if invalid
   *
   * @param mixed $value
   *
   * @return bool
   */
  public function isValidValue($value): bool;

  /**
   * Validate the data, return array of errors
   *
   * @param mixed $value
   * @param array $data additional data that may be required by some validators, eg. form data
   *
   * @return array
   */
  public function validateValue($value, array $data = []): array;

  /**
   * Validate the data, throwing an exception with the error
   *
   * @param mixed $value
   *
   * @throws Exception
   */
  public function assertValue($value);

  public function getErrors(): array;

  /**
   * @param ValidationException ...$errors
   *
   * @return DataHandler
   */
  public function addError(ValidationException ...$errors);

  /**
   * @return DataHandler
   */
  public function clearErrors();

  public function getInput(): HtmlElement;

  /**
   * @return string Input class modifier (will be prefixed with p-form__input-- up the chain)
   */
  public function getInputClass(): string;

  //Allows the input to be wrapped by the data handler
  public function wrapInput(HtmlElement $input): HtmlElement;
}
