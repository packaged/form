<?php
namespace Packaged\Tests\Form\Supporting;

use Packaged\Form\Csrf\CsrfForm;
use Packaged\Form\DataHandlers\TextDataHandler;

class CustomDecoratorForm extends CsrfForm
{
  public $name;
  public $email;

  protected function _initDataHandlers()
  {
    parent::_initDataHandlers();
    $this->name = new TextDataHandler();
    $this->email = new TextDataHandler();
  }
}
