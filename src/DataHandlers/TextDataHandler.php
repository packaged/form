<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Form\Decorators\InputDecorator;
use Packaged\Form\Decorators\Interfaces\DataHandlerDecorator;
use Packaged\Helpers\Strings;
use Packaged\Helpers\ValueAs;

class TextDataHandler extends AbstractDataHandler
{
  public function formatValue($value)
  {
    Strings::stringable($value);
    return parent::formatValue(ValueAs::string($value));
  }

  protected function _defaultDecorator(): DataHandlerDecorator
  {
    return new InputDecorator();
  }
}
