<?php
namespace Packaged\Form\Decorators;

use Packaged\Glimpse\Core\CustomHtmlTag;
use Packaged\Form\Form\Form;
use Packaged\Form\Form\Interfaces\FormDecorator;

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

  protected function _getElement()
  {
    $form = $this->getForm();

    $formElement = $this->_hydrateElement(CustomHtmlTag::build('form'))
      ->setAttribute('method', $form->getMethod())
      ->addClass('p-form');

    $action = $form->getAction();
    if($action)
    {
      $formElement->setAttribute('action', $action);
    }

    foreach($form->getDataHandlers() as $handler)
    {
      $formElement->appendContent($handler->getDecorator()->produceSafeHTML());
    }

    $formElement->appendContent($form->getSubmitDecorator()->produceSafeHTML());

    return $formElement;
  }
}
