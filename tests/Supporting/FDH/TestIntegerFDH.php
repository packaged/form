<?php
namespace PackagedUi\Tests\Form\Supporting\FDH;

use Packaged\Glimpse\Tags\Form\Input;
use PackagedUi\Form\DataHandlerDecorator;
use PackagedUi\Form\Decorators\InputDecorator;
use PackagedUi\Form\FormDataHandlers\AbstractFDH;

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

  protected function _defaultDecorator(): DataHandlerDecorator
  {
    $decorator = new InputDecorator();
    $decorator->setType(Input::TYPE_NUMBER);
    return $decorator;
  }
}
