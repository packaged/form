<?php
namespace Packaged\Form\Render;

use Packaged\Form\FormElement;

interface IFormElementRenderer
{
  public function render(FormElement $element);
}
