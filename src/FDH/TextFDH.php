<?php
namespace PackagedUi\Form\FDH;

use Packaged\Glimpse\Tags\Form\Input;

class TextFDH extends AbstractFDH
{
  public function getType(): string
  {
    return Input::TYPE_TEXT;
  }
}
