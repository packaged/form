<?php
namespace PackagedUi\Form\Decorators;

use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Glimpse\Tags\Form\Label;
use Packaged\Helpers\Strings;

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

  protected function _getElement()
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

    if($this->getType() === Input::TYPE_HIDDEN)
    {
      return $input;
    }

    $splitName = Strings::splitOnCamelCase($input->getName());
    $id = $input->getId();
    if(empty($id))
    {
      $id = strtolower(str_replace(' ', '-', $splitName) . '-' . Strings::randomString(3));
      $input->setId($id);
    }

    $label = Label::create();
    $label->setAttribute('for', $id);
    $label->setContent($this->_handler->getLabel() ?? Strings::titleize($splitName));
    return Div::create([$label, $input])->addClass('form-group');
  }
}
