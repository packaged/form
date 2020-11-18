<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Helpers\Strings;
use Packaged\Helpers\ValueAs;
use Packaged\Ui\Html\HtmlElement;

class SecureTextDataHandler extends TextDataHandler
{
  public function formatValue($value)
  {
    Strings::stringable($value);
    return parent::formatValue(ValueAs::string($value));
  }

  protected function _createBaseElement(): HtmlElement
  {
    return Input::create()->setType(Input::TYPE_PASSWORD);
  }

  protected function _generateInput(): HtmlElement
  {
    //Always clear a default password
    return parent::_generateInput()->setAttribute('value', '');
  }

}
