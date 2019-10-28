<?php
namespace Packaged\Form\Decorators;

use Packaged\Glimpse\Tags\Form\Input;

class PasswordInputDecorator extends InputDecorator
{
  protected $_type = Input::TYPE_PASSWORD;
}
