<?php
namespace PackagedUi\Form\Decorators;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Label;
use Packaged\Helpers\Strings;
use PackagedUi\Form\DataHandlers\Interfaces\DataHandler;
use PackagedUi\Form\Decorators\Interfaces\DataHandlerDecorator;

abstract class AbstractDataHandlerDecorator extends AbstractDecorator implements DataHandlerDecorator
{
  /**
   * @var DataHandler
   */
  protected $_handler;

  public function setHandler(DataHandler $handler)
  {
    $this->_handler = $handler;
    return $this;
  }

  abstract protected function _getInput(): HtmlTag;

  protected function _getLabel(): ?HtmlTag
  {
    $labelText = $this->_handler->getLabel()
      ?? Strings::titleize(Strings::splitOnCamelCase($this->_handler->getName()));
    if($labelText)
    {
      $label = Label::create();
      $label->setContent($labelText);
      return $label;
    }
    return null;
  }

  protected function _getElement()
  {
    $input = $this->_getInput();
    $label = $this->_getLabel();
    if($label)
    {
      $id = $input->getId();
      if(empty($id))
      {
        // create an id
        $name = $this->_handler->getName();
        $idSeed = $name
          ? str_replace(' ', '-', Strings::splitOnCamelCase($name)) . '-' . Strings::randomString(3)
          : Strings::pattern('XXXXX-0000-X0X0');

        $id = strtolower($idSeed);
        $input->setId($id);
      }
      $label->setAttribute('for', $id);
    }
    return $this->_formatElements($input, $label);
  }

  protected function _formatElements(HtmlTag $input, ?HtmlTag $label)
  {
    if($label)
    {
      return Div::create([$label, $input])->addClass('form-group');
    }
    return $input;
  }

}
