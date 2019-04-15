<?php
namespace Packaged\Form\Decorators;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\SafeHtml\SafeHtml;

class HiddenInputDecorator extends InputDecorator
{
  protected $_type = Input::TYPE_HIDDEN;

  public function produceSafeHTML(): SafeHtml
  {
    return SafeHtml::escape([$this->_getInputElement(), $this->_getErrorElement()], '');
  }
}
