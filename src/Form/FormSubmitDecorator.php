<?php
namespace Packaged\Form\Form;

use Packaged\Form\Decorators\AbstractDecorator;
use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Input;

class FormSubmitDecorator extends AbstractDecorator
{
  private $_value = 'Submit';

  public function setValue(string $value)
  {
    $this->_value = $value;
    return $this;
  }

  protected function _getElement()
  {
    return Div::create(
      Div::create(
        Input::create()->setType(Input::TYPE_SUBMIT)->setValue($this->_value)
      )->addClass('p-form--submit')
    )->addClass('p-form-field');
  }
}
