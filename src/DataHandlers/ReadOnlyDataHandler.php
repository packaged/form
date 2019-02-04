<?php
namespace PackagedUi\Form\DataHandlers;

use PackagedUi\Form\Decorators\Interfaces\DataHandlerDecorator;
use PackagedUi\Form\Decorators\ReadOnlyDecorator;

class ReadOnlyDataHandler extends AbstractDataHandler
{
  protected function _defaultDecorator(): DataHandlerDecorator
  {
    return new ReadOnlyDecorator();
  }
}
