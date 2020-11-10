<?php
namespace Packaged\Form\Decorators;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Helpers\Strings;
use Packaged\Helpers\ValueAs;
use Packaged\Ui\Html\HtmlElement;

class InputDecorator extends AbstractDataHandlerDecorator
{
  protected $_type;

  public function setInputType($type)
  {
    $this->_type = $type;
    return $this;
  }

  protected function _initInputElement(): HtmlTag
  {
    return Input::create();
  }

  protected function _configureInputElement(HtmlElement $input)
  {
    parent::_configureInputElement($input);
    if($input instanceof Input)
    {
      if($this->_type !== null)
      {
        $input->setType($this->_type);
      }

      if($input->getType() !== Input::TYPE_HIDDEN)
      {
        $input->setAttribute(
          'placeholder',
          $this->_handler->getPlaceholder()
            ?: Strings::titleize($this->_handler->getName())
        );
      }

      $val = $this->_handler->getValue();
      if($val === null)
      {
        $val = $this->_handler->getDefaultValue();
      }
      if($val !== null)
      {
        $input->setValue(ValueAs::string($val));
      }
    }
  }
}
