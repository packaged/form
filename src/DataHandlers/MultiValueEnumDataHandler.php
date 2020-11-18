<?php

namespace Packaged\Form\DataHandlers;

use Packaged\Form\Validators\HandlerEnumValidator;
use Packaged\Ui\Html\HtmlElement;
use Packaged\Validate\Validators\ArrayValidator;
use function is_array;

class MultiValueEnumDataHandler extends EnumDataHandler
{
  public function formatValue($value)
  {
    return parent::formatValue(is_array($value) ? $value : [$value]);
  }

  protected function _setupValidator()
  {
    $this->addValidator(new ArrayValidator(new HandlerEnumValidator($this)));
  }

  protected function _generateInput(): HtmlElement
  {
    return parent::_generateInput()->setAttribute('multiple', true);
  }
}
