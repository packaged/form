<?php
namespace PackagedUi\Form\Csrf;

use PackagedUi\Form\Form;

class CsrfForm extends Form
{
  /**
   * @var CsrfFDH
   */
  public $csrfToken;

  protected $_sessionSecret;

  public function __construct($sessionSecret)
  {
    $this->_sessionSecret = $sessionSecret;
    parent::__construct();
  }

  protected function _getCsrfSecret()
  {
    return md5(static::class);
  }

  protected function _initDataHandlers()
  {
    $this->csrfToken = new CsrfFDH($this->_getCsrfSecret(), $this->_sessionSecret);
  }
}