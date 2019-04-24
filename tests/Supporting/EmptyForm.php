<?php

namespace Packaged\Tests\Form\Supporting;

use Packaged\Form\Decorators\Interfaces\Decorator;
use Packaged\Form\Form\Form;

class EmptyForm extends Form
{
  protected function _initDataHandlers()
  {
  }

  public function getSubmitDecorator(): ?Decorator
  {
    return null;
  }
}
