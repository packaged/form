<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Form\Decorators\Interfaces\Decorator;
use Packaged\Form\Decorators\ReadOnlyDecorator;

class ReadOnlyDataHandler extends AbstractDataHandler
{
  protected function _defaultDecorator(): Decorator
  {
    return new ReadOnlyDecorator();
  }
}
