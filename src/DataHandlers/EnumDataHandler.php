<?php
namespace PackagedUi\Form\DataHandlers;

use PackagedUi\Form\DataHandlerDecorator;
use PackagedUi\Form\Decorators\SelectDecorator;

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

  public function validate($value)
  {
    parent::validate($value);

    if(!array_key_exists($value, $this->_options))
    {
      throw new \UnexpectedValueException("'$value'' is not a valid value");
    }
  }

}
