<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Helpers\ValueAs;
use Packaged\Ui\Html\HtmlElement;
use Packaged\Validate\Validators\IntegerValidator;

class IntegerDataHandler extends TextDataHandler
{
  public function formatValue($value)
  {
    return parent::formatValue(ValueAs::int($value));
  }

  protected function _createBaseElement(): HtmlElement
  {
    return Input::create()->setType(Input::TYPE_NUMBER);
  }

  protected function _setupValidators()
  {
    $this->addValidator(new IntegerValidator());
  }
}
