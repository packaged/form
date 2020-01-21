<?php
namespace Packaged\Tests\Form\Supporting;

use Packaged\Form\Csrf\CsrfForm;
use Packaged\Form\DataHandlers\SecureTextDataHandler;
use Packaged\Form\DataHandlers\TextDataHandler;

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
}
