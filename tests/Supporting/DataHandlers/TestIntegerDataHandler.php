<?php
namespace PackagedUi\Tests\Form\Supporting\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use PackagedUi\Form\DataHandlerDecorator;
use PackagedUi\Form\DataHandlers\AbstractDataHandler;
use PackagedUi\Form\Decorators\InputDecorator;

class TestIntegerDataHandler extends AbstractDataHandler
{
  const ERR_INVALID_NUMBER = "Invalid numeric value";

  public function validateValue($value)
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
