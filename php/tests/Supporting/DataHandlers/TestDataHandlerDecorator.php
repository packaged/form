<?php

namespace Packaged\Tests\Form\Supporting\DataHandlers;

use Packaged\Form\Decorators\DefaultDataHandlerDecorator;

class TestDataHandlerDecorator extends DefaultDataHandlerDecorator
{
  protected function _getContentForRender()
  {
    return '.';
  }
}
