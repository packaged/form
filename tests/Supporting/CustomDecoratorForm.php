<?php
namespace Packaged\Tests\Form\Supporting;

use Packaged\Form\Csrf\CsrfForm;
use Packaged\Form\DataHandlers\TextDataHandler;
use Packaged\Form\Form\Interfaces\FormDecorator;

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

  protected function _defaultDecorator(): FormDecorator
  {
    return new CustomFormDecorator();
  }
}
