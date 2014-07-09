<?php
namespace Packaged\Form\Render;

use Packaged\Form\Form;

class FormRenderer implements IFormRenderer
{
  protected $_elementGroupType;

  public function __construct($elementGroupType = null)
  {
    $this->_elementGroupType = $elementGroupType;
  }

  public function setElementGroupType($elementGroupType = 'dl')
  {
    $this->_elementGroupType = $elementGroupType;
    return $this;
  }

  public function getElementGroupType()
  {
    return $this->_elementGroupType;
  }

  public function renderOpening(Form $form)
  {
    $return = '<form class="packaged-form"';
    $return .= ' method="' . $form->getOption('method', 'post') . '"';
    $return .= ' action="' . $form->getOption('action', '/') . '"';
    $return .= ' name="' . $form->getOption('name') . '"';
    $return .= ' id="' . $form->getId() . '"';
    $return .= '>';
    return $return;
  }

  public function renderClosing(Form $form)
  {
    $return = '</form>';
    return $return;
  }

  public function renderElements(Form $form)
  {
    $return = '';
    if($this->_elementGroupType !== null)
    {
      $return = '<' . $this->_elementGroupType . '>';
    }

    foreach($form->getElements() as $element)
    {
      $return .= $element->render();
    }

    if($this->_elementGroupType !== null)
    {
      $return .= '</' . $this->_elementGroupType . '>';
    }
    return $return;
  }

  public function renderActions(Form $form)
  {
    $return = '<input type="submit" value="Submit"/>';
    return $return;
  }

  public function render(Form $form)
  {
    return $this->renderOpening($form)
    . $this->renderElements($form)
    . $this->renderActions($form)
    . $this->renderClosing($form);
  }
}
