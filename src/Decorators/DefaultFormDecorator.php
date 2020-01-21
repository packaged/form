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
}
