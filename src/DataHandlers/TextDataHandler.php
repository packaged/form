<?php
namespace PackagedUi\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Helpers\Strings;
use Packaged\Helpers\ValueAs;
use PackagedUi\Form\DataHandlerDecorator;
use PackagedUi\Form\Decorators\InputDecorator;

class TextDataHandler extends AbstractDataHandler
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
