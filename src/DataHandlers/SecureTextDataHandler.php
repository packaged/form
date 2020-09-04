<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Form\Decorators\Interfaces\Decorator;
use Packaged\Form\Decorators\PasswordInputDecorator;
use Packaged\Helpers\Strings;
use Packaged\Helpers\ValueAs;

class SecureTextDataHandler extends AbstractDataHandler
{
  public function formatValue($value)
  {
    Strings::stringable($value);
    return parent::formatValue(ValueAs::string($value));
  }

  protected function _defaultDecorator(): Decorator
  {
    return new PasswordInputDecorator();
  }
}
