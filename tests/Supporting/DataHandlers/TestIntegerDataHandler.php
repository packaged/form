<?php
namespace Packaged\Tests\Form\Supporting\DataHandlers;

use Packaged\Form\DataHandlers\AbstractDataHandler;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Ui\Html\HtmlElement;
use Packaged\Validate\Validators\IntegerValidator;
use Packaged\Validate\Validators\NullableValidator;

class TestIntegerDataHandler extends AbstractDataHandler
{
  protected function _generateInput(): HtmlElement
  {
    return Input::create()->setType(Input::TYPE_NUMBER);
  }

  protected function _setupValidators()
  {
    $this->addValidator(new NullableValidator(new IntegerValidator()));
  }
}
