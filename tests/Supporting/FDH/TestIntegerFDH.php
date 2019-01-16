<?php
namespace PackagedUi\Tests\Form\Supporting\FDH;

use Packaged\Glimpse\Tags\Form\Input;
use PackagedUi\Form\FDH\AbstractFDH;

class TestIntegerFDH extends AbstractFDH
{
  const ERR_INVALID_NUMBER = "Invalid numeric value";

  public function validate($value)
  {
    if($value !== null && !is_int($value))
    {
      throw new \UnexpectedValueException(self::ERR_INVALID_NUMBER);
    }
  }

  public function getType(): string
  {
    return Input::TYPE_NUMBER;
  }

}
