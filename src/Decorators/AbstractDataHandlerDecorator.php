<?php
namespace PackagedUi\Form\Decorators;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Label;
use Packaged\Glimpse\Tags\Lists\ListItem;
use Packaged\Glimpse\Tags\Lists\UnorderedList;
use Packaged\Helpers\Objects;
use Packaged\Helpers\Strings;
use PackagedUi\Form\DataHandlers\Interfaces\DataHandler;
use PackagedUi\Form\Decorators\Interfaces\DataHandlerDecorator;

abstract class AbstractDataHandlerDecorator extends AbstractDecorator implements DataHandlerDecorator
{
  /**
   * @var DataHandler
   */
  protected $_handler;
  /**
   * @var callable
   */
  protected $_formatCallback;

  public function setHandler(DataHandler $handler)
  {
    $this->_handler = $handler;
    return $this;
  }

  abstract protected function _getInputElement(): HtmlTag;

  protected function _getLabelElement(): ?HtmlTag
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
    $input = $this->_getInputElement();
    $label = $this->_getLabelElement();
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

    $errorTag = $this->_getErrorElement();
    return $this->_formatElements($input, $label, $errorTag);
  }

  protected function _getErrorElement()
  {
    $errorTag = null;
    $errors = $this->_handler->getErrors();
    if($errors)
    {
      $errorTag = UnorderedList::create()
        ->setContent(ListItem::collection(Objects::mpull($errors, 'getMessage')))
        ->addClass('validation-errors');
    }
    return $errorTag;
  }

  protected function _formatElements(HtmlTag $input, ?HtmlTag $label, ?HtmlTag $errors)
  {
    $callback = $this->_formatCallback;
    if(is_callable($callback))
    {
      return $callback($input, $label, $errors);
    }

    $return = Div::create()->addClass('form-group');
    if($label)
    {
      $return->appendContent($label);
    }
    if($errors)
    {
      $return->appendContent($errors);
    }
    $return->appendContent($input);
    return $return;
  }

  public function setFormatCallback(callable $callback)
  {
    $this->_formatCallback = $callback;
    return $this;
  }
}
