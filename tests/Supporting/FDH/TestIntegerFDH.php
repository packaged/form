<?php
namespace PackagedUi\Tests\Form\Supporting\FDH;

use PackagedUi\Form\FDH\AbstractFDH;

class TestIntegerFDH extends AbstractFDH
{
  const ERR_INVALID_NUMBER = "Invalid numeric value";

  public function validate()
  {
    if($this->getValue() !== null && !is_int($this->getValue()))
    {
      throw new \UnexpectedValueException(self::ERR_INVALID_NUMBER);
    }
  }

}
