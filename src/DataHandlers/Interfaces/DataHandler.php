<?php
namespace PackagedUi\Form\DataHandlers\Interfaces;

use PackagedUi\Form\Decorators\Interfaces\DataHandlerDecorator;

interface DataHandler
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
   * Validate the data, return true if valid, false if invalid
   *
   * @return bool
   */
  public function isValid(): bool;

  /**
   * Validate the data, return array of errors
   *
   * @return array
   */
  public function validate(): array;

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
   * @throws \Exception
   */
  public function assert();

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

  public function setName($name);

  public function getValue();

  public function setValue($value);

  public function getDecorator(): DataHandlerDecorator;

  public function getDefaultValue();

  public function setPlaceholder($placeholder);

  public function getPlaceholder();

  public function setLabel($label);

  public function getLabel();
}
