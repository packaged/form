<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Form\Decorators\InputDecorator;
use Packaged\Form\Decorators\Interfaces\Decorator;
use Packaged\Helpers\Strings;
use Packaged\Helpers\ValueAs;

class TextDataHandler extends AbstractDataHandler
{
  public function formatValue($value)
  {
    Strings::stringable($value);
    return parent::formatValue(ValueAs::string($value));
  }

  protected function _defaultDecorator(): Decorator
  {
    return new InputDecorator();
  }
}
