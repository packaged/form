<?php
namespace Packaged\Form\Decorators;

use Packaged\Form\DataHandlers\Interfaces\DataHandler;
use Packaged\Form\Form\Form;
use Packaged\Form\Form\Interfaces\FormDecorator;
use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Label;
use Packaged\Glimpse\Tags\Lists\ListItem;
use Packaged\Glimpse\Tags\Lists\UnorderedList;
use Packaged\Helpers\Objects;
use Packaged\SafeHtml\ISafeHtmlProducer;
use Packaged\Ui\Html\HtmlElement;
use PackagedUi\BemComponent\Bem;

class DefaultFormDecorator extends AbstractDecorator implements FormDecorator
{
  protected $_tag = 'form';
  /**
   * @var Form
   */
  protected $_form;
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

  protected function _prepareForProduce(): HtmlElement
  {
    $form = $this->getForm();
    $formAttr = $form->getAttributes();
    unset($formAttr['class']);
    $this->addAttributes($formAttr, true);
    $this->addClass($form->getClasses());

    $action = $form->getAction();
    if($action)
    {
      $this->setAttribute('action', $action);
    }
    $this->setAttribute('method', $form->getMethod());
    return parent::_prepareForProduce();
  }

  public function getForm(): Form
  {
    return $this->_form;
  }

  public function setForm(Form $form): FormDecorator
  {
    $this->_form = $form;
    return $this;
  }

  protected function _getTemplatedPhtmlClassList()
  {
    return array_unique([get_class($this->getForm()), $this->_getTemplatedPhtmlClass(), self::class]);
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
}
