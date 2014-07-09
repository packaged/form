<?php
namespace Packaged\Form\Render;

use Packaged\Form\Form;

class FormRenderer implements IFormRenderer
{
  protected $_groupType;

  public function __construct($groupType = 'dl')
  {
    $this->_groupType = $groupType;
  }

  public function setGroupType($groupType = 'dl')
  {
    $this->_groupType = $groupType;
    return $this;
  }

  public function getGroupType()
  {
    return $this->_groupType;
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
    $return = '<' . $this->_groupType . '>';

    foreach($form->getElements() as $element)
    {
      $return .= $element->render();
    }
    $return .= '</' . $this->_groupType . '>';
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
