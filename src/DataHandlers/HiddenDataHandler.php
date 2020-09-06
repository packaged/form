<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Form\Decorators\HiddenInputDecorator;
use Packaged\Form\Decorators\Interfaces\DataHandlerDecorator;

class HiddenDataHandler extends AbstractDataHandler
{
  protected function _defaultDecorator(): DataHandlerDecorator
  {
    return new HiddenInputDecorator();
  }
}
