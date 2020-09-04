<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Form\Decorators\HiddenInputDecorator;
use Packaged\Form\Decorators\Interfaces\Decorator;

class HiddenDataHandler extends AbstractDataHandler
{
  protected function _defaultDecorator(): Decorator
  {
    return new HiddenInputDecorator();
  }
}
