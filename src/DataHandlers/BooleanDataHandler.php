<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Form\Decorators\CheckboxDecorator;
use Packaged\Form\Decorators\Interfaces\Decorator;
use Packaged\Helpers\ValueAs;
use Packaged\Validate\Validators\BoolValidator;

class BooleanDataHandler extends AbstractDataHandler
{
  public function formatValue($value)
  {
    return parent::formatValue(ValueAs::bool($value));
  }

  protected function _defaultDecorator(): Decorator
  {
    return new CheckboxDecorator();
  }

  protected function _setupValidator()
  {
    $this->addValidator(new BoolValidator());
  }
}
