<?php
namespace Packaged\Form\Csrf;

use Generator;
use Packaged\Validate\AbstractValidator;
use Packaged\Validate\ValidationException;
use function password_verify;

class CsrfValidator extends AbstractValidator
{
  protected $_password;

  public function __construct(string $password)
  {
    $this->_password = $password;
  }

  protected function _doValidate($value): Generator
  {
    if(!password_verify($this->_password, $value))
    {
      yield new ValidationException('invalid or missing CSRF token');
    }
  }
}
