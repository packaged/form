<?php

namespace Packaged\Form\DataHandlers;

use Packaged\Form\Decorators\CheckboxDecorator;
use Packaged\Form\Decorators\Interfaces\DataHandlerDecorator;
use Packaged\Form\Validators\HandlerEnumValidator;
use Packaged\Validate\Validators\ArrayValidator;
use function is_array;

class MultiValueEnumDataHandler extends EnumDataHandler
{
  public function formatValue($value)
  {
    if(!is_array($value))
    {
      $value = [$value];
    }
    return parent::formatValue($value);
  }

  protected function _defaultDecorator(): DataHandlerDecorator
  {
    return new CheckboxDecorator();
  }

  protected function _setupValidator()
  {
    $this->addValidator(new ArrayValidator(new HandlerEnumValidator($this)));
  }
}
