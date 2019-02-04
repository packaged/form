<?php
namespace PackagedUi\Form\Form;

use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Input;
use PackagedUi\Form\Decorators\AbstractDecorator;

class FormSubmitDecorator extends AbstractDecorator
{
  protected function _getElement()
  {
    return Div::create(Input::create()->setType(Input::TYPE_SUBMIT)->setValue('Submit'))->addClass('form-group');
  }
}
