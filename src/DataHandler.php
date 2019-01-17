<?php
namespace PackagedUi\Form;

interface DataHandler
{
  public function isValidValue($value): bool;

  public function isValid(): bool;

  /**
   * Format a value into a safe value for the handler, throwing an exception if not compatible
   *
   * @param $value
   *
   * @return mixed
   *
   * @throws \Exception
   */
  public function formatValue($value);

  /**
   * Validate the data, throwing an exception with the error
   *
   * @param $value
   *
   * @throws \Exception
   */
  public function validate($value);

  public function getValue();

  public function setValue($vaue);

  public function getDecorator(): DataHandlerDecorator;

  public function getDefaultValue();

  public function getPlaceholder();

  public function getLabel();
}
