<?php
namespace Packaged\Tests\Form\Supporting\DataHandlers;

use Packaged\Form\DataHandlers\AbstractDataHandler;
use Packaged\Form\Decorators\InputDecorator;
use Packaged\Form\Decorators\Interfaces\Decorator;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Validate\Validators\IntegerValidator;
use Packaged\Validate\Validators\NullableValidator;

class TestIntegerDataHandler extends AbstractDataHandler
{
  protected function _setupValidator()
  {
    $this->addValidator(new NullableValidator(new IntegerValidator()));
  }

  protected function _defaultDecorator(): Decorator
  {
    $decorator = new InputDecorator();
    $decorator->getInput()->setAttribute('type', Input::TYPE_NUMBER);
    return $decorator;
  }
}
