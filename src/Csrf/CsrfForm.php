<?php
namespace Packaged\Form\Csrf;

use Packaged\Form\Form\Form;
use function md5;

class CsrfForm extends Form
{
  /**
   * @var CsrfDataHandler
   */
  public $csrfToken;

  protected $_sessionSecret;

  public function __construct($sessionSecret)
  {
    $this->_sessionSecret = $sessionSecret;
    parent::__construct();
  }

  protected function _initDataHandlers()
  {
    $this->csrfToken = new CsrfDataHandler($this->_getCsrfSecret(), $this->_sessionSecret);
  }

  protected function _getCsrfSecret()
  {
    return md5(static::class);
  }
}
