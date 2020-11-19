<?php
namespace Packaged\Form\Decorators;

use Packaged\Form\DataHandlers\HiddenDataHandler;
use Packaged\Form\DataHandlers\Interfaces\DataHandler;
use Packaged\Form\Decorators\Interfaces\DataHandlerDecorator;
use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Label;
use Packaged\Glimpse\Tags\Lists\ListItem;
use Packaged\Glimpse\Tags\Lists\UnorderedList;
use Packaged\Helpers\Objects;
use Packaged\SafeHtml\ISafeHtmlProducer;
use PackagedUi\BemComponent\Bem;

class DefaultDataHandlerDecorator extends AbstractDecorator implements DataHandlerDecorator
{
  protected $_handler;

  protected $_bem;

  public function __construct()
  {
    $this->_bem = Bem::block('p-form');
    $this->addClass($this->_bem->asString());
  }

  public function bem(): Bem
  {
    return $this->_bem;
  }

  public function setHandler(DataHandler $handler): DataHandlerDecorator
  {
    $this->_handler = $handler;
    return $this;
  }

  public function getHandler(): DataHandler
  {
    return $this->_handler;
  }

  protected function _renderLabel(DataHandler $handler): ?ISafeHtmlProducer
  {
    $label = $handler->getLabel();
    if($label === null || $label === '')
    {
      return null;
    }

    return Div::create(Label::create($label)->setAttribute('for', $handler->getId()))
      ->addClass($this->bem()->getElementName('label'));
  }

  protected function _getErrorMessages(DataHandler $handler): array
  {
    return Objects::mpull($handler->getErrors(), 'getMessage');
  }

  protected function _renderErrors(DataHandler $handler): ?ISafeHtmlProducer
  {
    if(empty($handler->getErrors()))
    {
      return null;
    }

    $errorMessages = $this->_getErrorMessages($handler);
    return Div::create(
      empty($errorMessages) ? null : UnorderedList::create()->setContent(ListItem::collection($errorMessages))
    )
      ->addClass($this->bem()->getElementName('errors'))
      ->addClass(empty($errorMessages) ? $this->bem()->getModifier('hidden', 'errors') : null); //Hide when no errors
  }

  protected function _renderInput(DataHandler $handler): ?ISafeHtmlProducer
  {
    return Div::create($handler->wrapInput($handler->getInput()))
      ->addClass($this->bem()->getElementName('input'), $this->bem()->getModifier($handler->getInputClass(), 'input'));
  }

  protected function _getContentForRender()
  {
    $handler = $this->getHandler();
    if($handler instanceof HiddenDataHandler)
    {
      return $handler->getInput();
    }

    //Pre render input, as the ID may be generated in this stage
    $input = $this->_renderInput($handler)->produceSafeHTML();

    return Div::create(
      $this->_renderLabel($handler),
      $this->_renderErrors($handler),
      $input
    )->addClass($this->bem()->getElementName('field'))
      ->addClass(empty($handler->getErrors()) ? null : $this->bem()->getModifier('error', 'field'));
  }

}
