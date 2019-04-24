<?php
namespace Packaged\Form\Decorators;

use Packaged\Form\DataHandlers\Interfaces\DataHandler;
use Packaged\Form\Decorators\Interfaces\DataHandlerDecorator;
use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Label;
use Packaged\Glimpse\Tags\Lists\ListItem;
use Packaged\Glimpse\Tags\Lists\UnorderedList;
use Packaged\Helpers\Objects;
use Packaged\Helpers\Strings;
use Packaged\Ui\Html\HtmlElement;

abstract class AbstractDataHandlerDecorator extends AbstractDecorator implements DataHandlerDecorator
{
  protected $_tag = 'div';
  /**
   * @var DataHandler
   */
  protected $_handler;

  protected $_input;
  protected $_label;

  public function __construct()
  {
    $this->_input = $this->_initInputElement();
    $this->_label = $this->_initLabelElement();
  }

  public function setHandler(DataHandler $handler)
  {
    $this->_handler = $handler;
    return $this;
  }

  abstract protected function _initInputElement(): HtmlTag;

  protected function _initLabelElement(): ?HtmlTag
  {
    return Label::create();
  }

  /**
   * @return HtmlTag
   */
  public function getInput(): HtmlElement
  {
    return $this->_input;
  }

  /**
   * @return HtmlTag|null
   */
  public function getLabel(): ?HtmlTag
  {
    return $this->_label;
  }

  protected function _configureInputElement(HtmlElement $input)
  {
    $id = $input->getId();
    $name = $this->_handler->getName();
    if(empty($id) && $this->_label && $name)
    {
      // create an id
      $id = strtolower(str_replace(' ', '-', Strings::splitOnCamelCase($name)) . '-' . Strings::randomString(3));
      $input->setId($id);
    }
    $input->setAttribute('name', $name, true);
  }

  protected function _configureLabelElement(HtmlTag $label)
  {
    if(empty($label->getContent(true)))
    {
      $labelText = $this->_handler->getLabel()
        ?? Strings::titleize(Strings::splitOnCamelCase($this->_handler->getName()));
      if($labelText)
      {
        $label->setContent($labelText);
      }
    }
    $label->setAttribute('for', $this->_input->getId(), true);
  }

  protected function _prepareForProduce(): HtmlElement
  {
    $this->addClass('p-form-field');
    return parent::_prepareForProduce();
  }

  protected function _getContentForRender()
  {
    $input = $this->_input;
    $this->_configureInputElement($input);
    if($this->_label)
    {
      $this->_configureLabelElement($this->_label);
    }

    $errorTag = $this->_getErrorElement();
    return $this->_formatElements($input, $this->_label, $errorTag);
  }

  protected function _getErrorElement()
  {
    $errorTag = null;
    $errors = $this->_handler->getErrors();
    if($errors)
    {
      $errorTag = UnorderedList::create()
        ->setContent(ListItem::collection(Objects::mpull($errors, 'getMessage')));
    }
    return $errorTag;
  }

  protected function _formatElements(HtmlTag $input, ?HtmlTag $label, ?HtmlTag $errors)
  {
    $return = ['label' => null, 'errors' => null, 'input' => null];
    if($label)
    {
      $return['label'] = Div::create($label)->addClass('p-form--label');
    }
    if($errors)
    {
      $return['errors'] = Div::create($errors)->addClass('p-form--errors');
    }
    $return['input'] = Div::create($input)->addClass('p-form--input');
    return $return;
  }
}
