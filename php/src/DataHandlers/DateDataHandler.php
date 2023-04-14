<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Ui\Html\HtmlElement;

class DateDataHandler extends TextDataHandler
{
  protected function _createBaseElement(): HtmlElement
  {
    return Input::create()->setType(Input::TYPE_DATE);
  }
}
