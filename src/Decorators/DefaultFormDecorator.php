<?php
namespace PackagedUi\Form\Decorators;

use Packaged\Glimpse\Core\CustomHtmlTag;
use PackagedUi\Form\Form\Form;
use PackagedUi\Form\Form\Interfaces\FormDecorator;

class DefaultFormDecorator extends AbstractDecorator implements FormDecorator
{
  /**
   * @var Form
   */
  protected $_form;

  public function setForm(Form $form): FormDecorator
  {
    $this->_form = $form;
    return $this;
  }

  public function getForm(): Form
  {
    return $this->_form;
  }

  public function render(): string
  {
    return (string)$this->produceSafeHTML();
  }

  public function produceSafeHTML()
  {
    $form = $this->getForm();

    $formElement = $this->_hydrateElement(CustomHtmlTag::build('form'))
      ->setAttribute('method', $form->getMethod());

    $action = $form->getAction();
    if($action)
    {
      $formElement->setAttribute('action', $action);
    }

    foreach($form->getDataHandlers() as $handler)
    {
      $formElement->appendContent($handler->getDecorator()->produceSafeHTML());
    }
    return $formElement;
  }
}
