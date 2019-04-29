<?php
namespace Packaged\Form\Validators;

use Packaged\Form\DataHandlers\EnumDataHandler;
use Packaged\Validate\Validators\EnumValidator;
use function array_keys;

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
