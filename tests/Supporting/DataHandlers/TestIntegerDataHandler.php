<?php
namespace Packaged\Tests\Form\Supporting\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Validate\Validators\IntegerValidator;
use Packaged\Validate\Validators\NullableValidator;
use Packaged\Form\DataHandlers\AbstractDataHandler;
use Packaged\Form\Decorators\InputDecorator;
use Packaged\Form\Decorators\Interfaces\DataHandlerDecorator;

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
