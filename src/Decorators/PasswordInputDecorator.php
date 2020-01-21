<?php
namespace Packaged\Form\Decorators;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Ui\Html\HtmlElement;

class PasswordInputDecorator extends InputDecorator
{
  protected $_type = Input::TYPE_PASSWORD;

  protected function _configureInputElement(HtmlElement $input)
  {
    parent::_configureInputElement($input);
    if($input instanceof Input)
    {
      //Never set the value of a password input
      $input->setValue('');
    }
  }
}
