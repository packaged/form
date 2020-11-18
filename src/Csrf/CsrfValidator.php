<?php
namespace Packaged\Form\Csrf;

use Generator;
use Packaged\Validate\AbstractValidator;
use Packaged\Validate\ValidationException;

class CsrfValidator extends AbstractValidator
{
  protected $_password;
  protected $_expiryMinutes;

  public function __construct(string $password, ?int $expiryMinutes)
  {
    $this->_password = $password;
    $this->_expiryMinutes = $expiryMinutes;
  }

  protected $_expiredError = 'Anti-Forgery token expired';
  protected $_invalidError = 'Anti-Forgery token missing or invalid';

  protected function _doValidate($value): Generator
  {
    $timestamp = base_convert(substr($value, 32), 36, 10);
    if($this->_expiryMinutes !== null && $timestamp < time() - ($this->_expiryMinutes * 60))
    {
      yield new ValidationException($this->_expiredError);
    }

    if(CsrfDataHandler::generateHash($this->_password, $timestamp) !== $value)
    {
      yield new ValidationException($this->_invalidError);
    }
  }
}
