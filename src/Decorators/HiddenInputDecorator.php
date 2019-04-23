<?php
namespace Packaged\Form\Decorators;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Ui\Html\HtmlElement;

class HiddenInputDecorator extends InputDecorator
{
  protected $_tag = '';

  protected function _formatElements(HtmlTag $input, ?HtmlTag $label, ?HtmlTag $errors)
  {
    return [$input, $errors];
  }

  protected function _initLabelElement(): ?HtmlTag
  {
    return null;
  }

  protected function _configureInputElement(HtmlElement $input)
  {
    $input->setAttribute('type', Input::TYPE_HIDDEN);
    parent::_configureInputElement($input);
  }
}
