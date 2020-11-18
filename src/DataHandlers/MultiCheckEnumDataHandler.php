<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Ui\Html\HtmlElement;

class MultiCheckEnumDataHandler extends MultiValueEnumDataHandler
{
  protected function _generateInput(): HtmlElement
  {
    //TODO: Find a way for multiple inputs to work for a single input
  }
}
