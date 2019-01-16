<?php
namespace PackagedUi\Form;

interface FormDataHandler
{
  public function isValidValue($value): bool;

  public function isValid(): bool;

  /**
   * Validate the data, throwing an exception with the error
   *
   * @throws \Exception
   */
  public function validate();

  public function getValue();

  public function setValue($vaue);

  public function getType(): string;
}
