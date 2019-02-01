<?php
namespace PackagedUi\Form\Decorators;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Form\Input;

class InputDecorator extends AbstractDataHandlerDecorator
{
  protected $_type = Input::TYPE_TEXT;

  /**
   * @return mixed
   */
  public function getType()
  {
    return $this->_type;
  }

  /**
   * @param mixed $type
   *
   * @return InputDecorator
   */
  public function setType($type)
  {
    $this->_type = $type;
    return $this;
  }

  protected function _getInputElement(): HtmlTag
  {
    $input = Input::create();
    $input->setId($this->getId());
    $input->setType($this->getType());
    $input->setName($this->_handler->getName());
    if($this->_handler->getValue() !== null)
    {
      $input->setValue($this->_handler->getValue());
    }
    else
    {
      $default = $this->_handler->getDefaultValue();
      if($default !== null)
      {
        $input->setValue($default);
      }
    }
    return $input;
  }

  protected function _getLabelElement(): ?HtmlTag
  {
    if($this->getType() === Input::TYPE_HIDDEN)
    {
      return null;
    }
    return parent::_getLabelElement();
  }

}
