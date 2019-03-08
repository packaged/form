<?php
namespace Packaged\Form\Decorators;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Span;

class ReadOnlyDecorator extends AbstractDataHandlerDecorator
{
  protected function _getInputElement(): HtmlTag
  {
    return Span::create($this->_handler->getValue())
      ->setId($this->getId());
  }
}
