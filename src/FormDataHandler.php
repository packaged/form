<?php
namespace PackagedUi\Form;

use Packaged\Glimpse\Core\HtmlTag;

interface FormDataHandler
{
  public function isValidValue($value): bool;

  public function isValid(): bool;

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

  public function getType(): string;

  public function getElement(): HtmlTag;
}
