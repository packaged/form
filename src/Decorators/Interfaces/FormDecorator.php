<?php
namespace Packaged\Form\Decorators\Interfaces;

use Packaged\Form\Form\Form;

interface FormDecorator extends Decorator
{
  public function setForm(Form $form): FormDecorator;

  public function getForm(): Form;
}
