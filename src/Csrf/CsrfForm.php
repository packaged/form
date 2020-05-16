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

  //Number of minutes the csrf token should be valid for.  Setting to null for unlimited time
  protected $_tokenExpiryMinutes = 60;

  public function __construct($sessionSecret)
  {
    $this->_sessionSecret = $sessionSecret;
    parent::__construct();
  }

  protected function _initDataHandlers()
  {
    $this->csrfToken = new CsrfDataHandler($this->_getCsrfSecret(), $this->_sessionSecret, $this->_tokenExpiryMinutes);
  }

  protected function _getCsrfSecret()
  {
    return md5(static::class);
  }
}
