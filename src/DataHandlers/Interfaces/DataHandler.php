<?php
namespace Packaged\Form\DataHandlers\Interfaces;

use Packaged\Validate\IValidatable;
use Packaged\Validate\ValidationException;
use Packaged\Form\Decorators\Interfaces\DataHandlerDecorator;

interface DataHandler extends IValidatable
{
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
   *
   * @return array
   */
  public function validateValue($value): array;

  /**
   * Validate the data, throwing an exception with the error
   *
   * @param mixed $value
   *
   * @throws \Exception
   */
  public function assertValue($value);

  /**
   * Format a value into a safe value for the handler, throwing an exception if not compatible
   *
   * @param $value
   *
   * @return mixed
   */
  public function formatValue($value);

  public function getName(): ?string;

  /**
   * @param string $name
   *
   * @return DataHandler
   */
  public function setName($name);

  public function getValue();

  /**
   * @param mixed $value
   *
   * @return DataHandler
   */
  public function setValue($value);

  public function getDecorator(): DataHandlerDecorator;

  public function getDefaultValue();

  /**
   * @param string $placeholder
   *
   * @return DataHandler
   */
  public function setPlaceholder($placeholder);

  public function getPlaceholder();

  /**
   * @param string $label
   *
   * @return DataHandler
   */
  public function setLabel($label);

  public function getLabel();

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
}
