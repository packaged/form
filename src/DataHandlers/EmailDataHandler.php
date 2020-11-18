<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Ui\Html\HtmlElement;
use Packaged\Validate\Validators\EmailValidator;

class EmailDataHandler extends TextDataHandler
{
  protected function _createBaseElement(): HtmlElement
  {
    return Input::create()->setType(Input::TYPE_EMAIL);
  }

  protected function _setupValidator()
  {
    $this->addValidator(new EmailValidator());
  }
}
