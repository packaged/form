<?php

namespace Packaged\Form\DataHandlers;

use Packaged\Form\Validators\HandlerEnumValidator;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Glimpse\Tags\Form\Select;
use Packaged\Ui\Html\HtmlElement;
use Packaged\Validate\Validators\ArrayValidator;
use function is_array;

class MultiValueEnumDataHandler extends EnumDataHandler
{
  public function formatValue($value)
  {
    $return = [];
    if($value === null)
    {
      return $return;
    }
    else if(is_array($value))
    {
      foreach($value as $k => $v)
      {
        $return[$k] = parent::formatValue($v);
      }
    }
    else
    {
      $return[] = parent::formatValue($value);
    }

    return $return;
  }

  public function getDefaultValue()
  {
    return [];
  }

  protected function _setupValidators()
  {
    $this->addValidator(new ArrayValidator(new HandlerEnumValidator($this)));
  }

  protected function _inputType()
  {
    return Input::TYPE_CHECKBOX;
  }

  protected function _inputName()
  {
    return parent::_inputName() . '[]';
  }

  protected function _isSelectedOption($option): bool
  {
    return in_array($option, (array)$this->getValueWithDefault());
  }

  protected function _generateInput(): HtmlElement
  {
    $parentInput = parent::_generateInput();
    if($this->_inputStyle === self::INPUT_STYLE_SPLIT)
    {
      return $parentInput;
    }

    if($parentInput instanceof Select)
    {
      $parentInput->setAttribute('multiple', true);
    }

    return $parentInput;
  }
}
