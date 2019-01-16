<?php
namespace PackagedUi\Form\FDH;

use PackagedUi\Form\DataHandlerDecorator;
use PackagedUi\Form\Decorators\SelectDecorator;

class EnumFDH extends AbstractFDH
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

  public function getDefaultDecorator(): DataHandlerDecorator
  {
    return new SelectDecorator();
  }
}
