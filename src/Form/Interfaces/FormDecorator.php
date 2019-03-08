<?php
namespace Packaged\Form\Form\Interfaces;

use Packaged\Form\Decorators\Interfaces\Decorator;
use Packaged\Form\Form\Form;

interface FormDecorator extends Decorator
{
  public function setForm(Form $form): FormDecorator;

  public function getForm(): Form;
}
