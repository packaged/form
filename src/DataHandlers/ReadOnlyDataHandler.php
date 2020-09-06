<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Form\Decorators\Interfaces\DataHandlerDecorator;
use Packaged\Form\Decorators\ReadOnlyDecorator;

class ReadOnlyDataHandler extends AbstractDataHandler
{
  protected function _defaultDecorator(): DataHandlerDecorator
  {
    return new ReadOnlyDecorator();
  }
}
