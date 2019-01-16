<?php
namespace PackagedUi\Form\FDH;

use Packaged\Glimpse\Tags\Form\Input;
use PackagedUi\Form\DataHandlerDecorator;
use PackagedUi\Form\Decorators\InputDecorator;

class TextFDH extends AbstractFDH
{
  protected function _defaultDecorator(): DataHandlerDecorator
  {
    $decorator = new InputDecorator();
    $decorator->setType(Input::TYPE_TEXT);
    return $decorator;
  }
}
