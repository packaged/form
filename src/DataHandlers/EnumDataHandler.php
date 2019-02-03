<?php
namespace PackagedUi\Form\DataHandlers;

use PackagedUi\Form\Decorators\Interfaces\DataHandlerDecorator;
use PackagedUi\Form\Decorators\SelectDecorator;
use PackagedUi\Form\Validators\HandlerEnumValidator;

class EnumDataHandler extends AbstractDataHandler
{
  protected $_options = [];

  public function setOptions(array $value)
  {
    $this->_options = $value;
    return $this;
  }

  public function addOption($value, $display = null)
  {
    $this->_options[$value] = $display ?? $value;
    return $this;
  }

  public function getOptions()
  {
    return $this->_options;
  }

  protected function _defaultDecorator(): DataHandlerDecorator
  {
    return new SelectDecorator();
  }

  protected function _setupValidator()
  {
    $this->addValidator(new HandlerEnumValidator($this));
  }
}
