<?php
namespace Packaged\Form\Render;

use Packaged\Form\Form;

interface IFormRenderer
{
  public function render(Form $form);
}
