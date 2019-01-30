<?php
namespace PackagedUi\Form\Form\Interfaces;

use PackagedUi\Form\Decorators\Interfaces\Decorator;
use PackagedUi\Form\Form\Form;

interface FormDecorator extends Decorator
{
  public function setForm(Form $form): FormDecorator;

  public function getForm(): Form;
}
