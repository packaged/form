<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Ui\Html\HtmlElement;

class ReadOnlyDataHandler extends TextDataHandler
{
  protected function _createBaseElement(): HtmlElement
  {
    return Input::create()->setAttribute('readonly', true);
  }

}
