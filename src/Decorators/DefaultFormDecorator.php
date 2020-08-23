<?php
namespace Packaged\Form\Decorators;

use Packaged\Form\DataHandlers\HiddenDataHandler;
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

  public function renderHandler(DataHandler $handler): ISafeHtmlProducer
  {
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

  protected function _renderLabel(DataHandler $handler): ?ISafeHtmlProducer
  {
    $label = $handler->getLabel();
    if($label === null || $label === '')
    {
      return null;
    }

    return Div::create(
      Label::create($label)->setAttribute('for', $handler->getId())
    )->addClass($this->bem()->getElementName('label'));
  }

  protected function _renderErrors(DataHandler $handler): ?ISafeHtmlProducer
  {
    $errors = $handler->getErrors();
    if(empty($errors))
    {
      return null;
    }

    return Div::create(
      empty($errors) ? null :
        UnorderedList::create()->setContent(ListItem::collection(Objects::mpull($errors, 'getMessage')))
    )->addClass($this->bem()->getElementName('errors'))
      ->addClass(empty($errors) ? $this->bem()->getModifier('hidden', 'errors') : null); //Hide when no errors
  }

  protected function _renderInput(DataHandler $handler): ?ISafeHtmlProducer
  {
    $input = $handler->getInput();
    if($input === null)
    {
      return null;
    }

    return Div::create($input)->addClass($this->bem()->getElementName('input'));
  }
}
