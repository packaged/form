<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Form\Decorators\Interfaces\Decorator;
use Packaged\Form\Decorators\SelectDecorator;
use Packaged\Form\Validators\HandlerEnumValidator;

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

  protected function _defaultDecorator(): Decorator
  {
    return new SelectDecorator();
  }

  protected function _setupValidator()
  {
    $this->addValidator(new HandlerEnumValidator($this));
  }
}
