<?php
namespace PackagedUi\Form\Validators;

use Packaged\Validate\Validators\EnumValidator;
use PackagedUi\Form\DataHandlers\EnumDataHandler;

class HandlerEnumValidator extends EnumValidator
{
  /**
   * @var EnumDataHandler
   */
  private $_handler;

  public function __construct(EnumDataHandler $enumHandler, bool $caseSensitive = false)
  {
    $this->_handler = $enumHandler;
    parent::__construct($this->_getAllowedValues(), $caseSensitive);
  }

  protected function _getAllowedValues()
  {
    return array_keys($this->_handler->getOptions());
  }
}
