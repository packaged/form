<?php
namespace Packaged\Form\Decorators;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Span;
use Packaged\Ui\Html\HtmlElement;

class ReadOnlyDecorator extends AbstractDataHandlerDecorator
{
  protected function _initInputElement(): HtmlTag
  {
    return Span::create();
  }

  protected function _configureInputElement(HtmlElement $input)
  {
    if($input instanceof HtmlTag)
    {
      $input->setContent($this->_handler->getValue());
    }
    parent::_configureInputElement($input);
    $input->removeAttribute('name');
  }
}
