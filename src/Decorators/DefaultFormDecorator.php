<?php
namespace Packaged\Form\Decorators;

use Packaged\Form\Form\Form;
use Packaged\Form\Form\Interfaces\FormDecorator;
use Packaged\Ui\Html\HtmlElement;

class DefaultFormDecorator extends AbstractDecorator implements FormDecorator
{
  protected $_tag = 'form';
  /**
   * @var Form
   */
  protected $_form;

  public function __construct()
  {
    $this->addClass('p-form');
  }

  public function setForm(Form $form): FormDecorator
  {
    $this->_form = $form;
    return $this;
  }

  public function getForm(): Form
  {
    return $this->_form;
  }

  protected function _prepareForProduce(): HtmlElement
  {
    $form = $this->getForm();

    $action = $form->getAction();
    if($action)
    {
      $this->setAttribute('action', $action);
    }
    $this->setAttribute('method', $form->getMethod());
    return parent::_prepareForProduce();
  }

  protected function _getContentForRender()
  {
    $form = $this->getForm();

    $content = [];
    foreach($form->getDataHandlers() as $handler)
    {
      $content[] = $handler->getDecorator()->produceSafeHTML();
    }

    $submitDecorator = $form->getSubmitDecorator();
    if($submitDecorator)
    {
      $content[] = $submitDecorator->produceSafeHTML();
    }

    return $content;
  }
}
