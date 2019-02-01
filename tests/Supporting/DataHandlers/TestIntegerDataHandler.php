<?php
namespace PackagedUi\Tests\Form\Supporting\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Validate\Validators\IntegerValidator;
use Packaged\Validate\Validators\NullableValidator;
use PackagedUi\Form\DataHandlers\AbstractDataHandler;
use PackagedUi\Form\Decorators\InputDecorator;
use PackagedUi\Form\Decorators\Interfaces\DataHandlerDecorator;

class TestIntegerDataHandler extends AbstractDataHandler
{
  protected function _setupValidator()
  {
    $this->addValidator(new NullableValidator(new IntegerValidator()));
  }

  protected function _defaultDecorator(): DataHandlerDecorator
  {
    $decorator = new InputDecorator();
    $decorator->setType(Input::TYPE_NUMBER);
    return $decorator;
  }
}
