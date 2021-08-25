<?php
namespace Packaged\Tests\Form\Supporting;

use Packaged\Form\Csrf\CsrfForm;
use Packaged\Form\DataHandlers\SecureTextDataHandler;
use Packaged\Form\DataHandlers\TextDataHandler;
use Packaged\Form\Decorators\Interfaces\DataHandlerDecorator;
use Packaged\Form\Decorators\Interfaces\FormDecorator;
use Packaged\Tests\Form\Supporting\DataHandlers\TestDataHandlerDecorator;

class CustomDecoratorForm extends CsrfForm
{
  /** @var TextDataHandler */
  public $name;
  /** @var TextDataHandler */
  public $email;
  /** @var SecureTextDataHandler */
  public $password;

  protected function _initDataHandlers()
  {
    parent::_initDataHandlers();
    $this->name = new TextDataHandler();
    $this->name->setId("name-input");
    $this->email = new TextDataHandler();
    $this->password = new SecureTextDataHandler();
  }

  protected function _defaultDecorator(): FormDecorator
  {
    return new CustomFormDecorator();
  }

  protected function _defaultHandlerDecorator(): DataHandlerDecorator
  {
    return TestDataHandlerDecorator::i();
  }
}
