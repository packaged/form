<?php
namespace Packaged\Form\Decorators;

use Packaged\Form\DataHandlers\AbstractDataHandler;
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
use function array_diff_key;
use function array_fill_keys;
use function array_key_exists;
use function array_merge;
use function str_replace;
use function strtolower;

abstract class AbstractDataHandlerDecorator extends AbstractDecorator implements DataHandlerDecorator
{
  protected $_tag = 'div';
  /**
   * @var DataHandler|AbstractDataHandler
   */
  protected $_handler;

  protected $_input;
  protected $_label;

  protected $_elementOrder = [self::LABEL, self::ERRORS, self::INPUT];

  protected $_formatCallback;

  protected $_rendered;

  public function __construct()
  {
    $this->_formatCallback = function ($return) { return $return; };
    $this->_input = $this->_initInputElement();
    $this->_label = $this->_initLabelElement();
  }

  abstract protected function _initInputElement(): HtmlTag;

  protected function _initLabelElement(): ?HtmlTag
  {
    return Label::create();
  }

  public function setHandler(DataHandler $handler)
  {
    $this->_handler = $handler;
    return $this;
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

  protected function _prepareForProduce(): HtmlElement
  {
    $this->addClass('p-form-field');
    if($this->_handler->getErrors())
    {
      $this->addClass('p-form-field--error');
    }
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
    $callback = $this->_formatCallback;
    $this->_rendered = $callback($this->_formatElements($input, $this->_label, $errorTag));
    return $this->getElements();
  }

  public function getElements()
  {
    if($this->_rendered === null)
    {
      $this->render();
    }
    return $this->_rendered;
  }

  public function getElement($type, $default = null)
  {
    $eles = $this->getElements();
    return $eles[$type] ?? $default;
  }

  protected function _configureInputElement(HtmlElement $input)
  {
    $id = $input->getId();
    if(empty($id) && $this->_handler instanceof AbstractDataHandler)
    {
      $id = $this->_handler->getId();
    }

    $name = $this->_handler->getName();

    if(empty($id) && $this->_label && $name)
    {
      // create an id
      $id = strtolower(str_replace(' ', '-', Strings::splitOnCamelCase($name)) . '-' . Strings::randomString(3));
    }

    if(!empty($id))
    {
      $this->_handler->setId($id);
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
    $return = array_fill_keys($this->getElementOrder(), null);
    if(array_key_exists(self::LABEL, $return) && $label)
    {
      $return[self::LABEL] = Div::create($label)->addClass('p-form--label');
    }
    if(array_key_exists(self::ERRORS, $return) && $errors)
    {
      $return[self::ERRORS] = Div::create($errors)->addClass('p-form--errors');
    }
    if(array_key_exists(self::INPUT, $return))
    {
      $return[self::INPUT] = Div::create($input)->addClass('p-form--input');
    }

    // array merge, but don't replace existing
    return array_merge(
      $return,
      array_diff_key([self::LABEL => null, self::ERRORS => null, self::INPUT => null], $return)
    );
  }

  /**
   * @return array
   */
  public function getElementOrder(): array
  {
    return $this->_elementOrder;
  }

  /**
   * @param array $elementOrder
   *
   * @return $this
   */
  public function setElementOrder(array $elementOrder)
  {
    $this->_elementOrder = $elementOrder;
    return $this;
  }

  public function setFormatCallback(callable $callback)
  {
    $this->_formatCallback = $callback;
    return $this;
  }
}
