<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Textarea;
use Packaged\Ui\Html\HtmlElement;

class MultiLineTextDataHandler extends TextDataHandler
{
  protected $_rows;

  public function setRows(int $rows): MultiLineTextDataHandler
  {
    $this->_rows = $rows;
    return $this;
  }

  protected function _createBaseElement(): HtmlElement
  {
    return new Textarea();
  }

  protected function _generateInput(): HtmlElement
  {
    $ele = parent::_generateInput();

    if($ele instanceof Textarea)
    {
      if($this->_rows)
      {
        $ele->setAttribute('rows', $this->_rows);
      }
      $ele->removeAttribute('value');
      $ele->setContent($this->formatValue($this->getValue()));
    }

    return $ele;
  }
}
