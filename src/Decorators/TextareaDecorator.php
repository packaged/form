<?php
namespace Packaged\Form\Decorators;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Form\Textarea;
use Packaged\Helpers\Strings;
use Packaged\Ui\Html\HtmlElement;

class TextareaDecorator extends AbstractDataHandlerDecorator
{
  protected function _initInputElement(): HtmlTag
  {
    return Textarea::create();
  }

  protected function _configureInputElement(HtmlElement $input)
  {
    parent::_configureInputElement($input);
    if($input instanceof Textarea)
    {
      $input->setAttribute(
        'placeholder',
        $this->_handler->getPlaceholder() ?: Strings::titleize($this->_handler->getName())
      );

      if($this->_handler->getValue() !== null)
      {
        $input->setContent($this->_handler->getValue());
      }
      else
      {
        $default = $this->_handler->getDefaultValue();
        if($default !== null)
        {
          $input->setContent($default);
        }
      }
    }
  }
}
