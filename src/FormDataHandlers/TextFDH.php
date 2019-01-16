<?php
namespace PackagedUi\Form\FormDataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Helpers\Strings;
use Packaged\Helpers\ValueAs;
use PackagedUi\Form\DataHandlerDecorator;
use PackagedUi\Form\Decorators\InputDecorator;

class TextFDH extends AbstractFDH
{
  public function formatValue($value)
  {
    Strings::stringable($value);
    return parent::formatValue(ValueAs::string($value));
  }

  protected function _defaultDecorator(): DataHandlerDecorator
  {
    $decorator = new InputDecorator();
    $decorator->setType(Input::TYPE_TEXT);
    return $decorator;
  }
}
