<?php
namespace Packaged\Form\Decorators;

use Packaged\Form\DataHandlers\AbstractDataHandler;
use Packaged\Form\DataHandlers\HiddenDataHandler;
use Packaged\Form\DataHandlers\Interfaces\DataHandler;
use Packaged\Form\Decorators\Interfaces\DataHandlerDecorator;
use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Label;
use Packaged\Glimpse\Tags\Lists\ListItem;
use Packaged\Glimpse\Tags\Lists\UnorderedList;
use Packaged\Helpers\Objects;
use Packaged\SafeHtml\ISafeHtmlProducer;
use Packaged\SafeHtml\SafeHtml;
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

  public function renderGuidance(): ?ISafeHtmlProducer
  {
    $guidance = $this->getHandler()->getGuidance();
    return $guidance ? Div::create($guidance)->addClass($this->bem()->getElementName('guidance')) : null;
  }

  public function renderLabel(): ?ISafeHtmlProducer
  {
    $label = $this->getHandler()->getLabel();
    return Div::create(Label::create($label)->setAttribute('for', $this->getHandler()->getId()))
      ->addClass($this->bem()->getElementName('label'));
  }

  public function getErrorMessages(): array
  {
    return Objects::mpull($this->getHandler()->getErrors(), 'getMessage');
  }

  public function renderErrors(): ?ISafeHtmlProducer
  {
    $container = Div::create()->addClass($this->bem()->getElementName('errors'));

    $errorMessages = $this->getErrorMessages();
    if(empty($errorMessages))
    {
      $container->addClass($this->bem()->getModifier('hidden', 'errors'));
    }
    else
    {
      $container->appendContent(UnorderedList::create()->setContent(ListItem::collection($errorMessages)));
    }
    return $container;
  }

  public function renderInput(): ?ISafeHtmlProducer
  {
    $handler = $this->getHandler();
    return Div::create($handler->wrapInput($handler->getInput()))
      ->addClass(
        $this->bem()->getElementName('input'),
        $this->bem()->getModifier($handler->getInputClass(), 'input')
      );
  }

  public function wrapField(SafeHtml $content)
  {
    $handler = $this->getHandler();

    $div = Div::create($content)
      ->setAttribute('handler-name', $handler->getName())
      ->addClass($this->bem()->getElementName('field'));

    $validators = null;
    if($handler instanceof AbstractDataHandler)
    {
      $validators = $handler->getValidators();
    }

    if($validators)
    {
      $div->setAttribute('validation', base64_encode(json_encode($validators)));
    }

    if(!empty($handler->getErrors()))
    {
      $div->addClass($this->bem()->getModifier('error', 'field'));
    }
    return $div;
  }

  protected function _getContentForRender()
  {
    $handler = $this->getHandler();
    if($handler instanceof HiddenDataHandler)
    {
      return $handler->getInput();
    }

    return $this->wrapField(new SafeHtml($this->_renderTemplate()));
  }

}
