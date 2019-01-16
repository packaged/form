<?php
namespace PackagedUi\Form\Decorators;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Form\Input;
use PackagedUi\Form\DataHandlerDecorator;
use PackagedUi\Form\DataHandler;

class InputDecorator implements DataHandlerDecorator
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

  public function buildElement(DataHandler $handler, array $options = null): HtmlTag
  {
    $element = new Input();
    $element->setType($this->getType());
    if($handler->getValue() !== null)
    {
      $element->setValue($handler->getValue());
    }
    return $element;
  }

}
