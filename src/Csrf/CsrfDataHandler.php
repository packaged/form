<?php
namespace Packaged\Form\Csrf;

use Packaged\Form\DataHandlers\HiddenDataHandler;

class CsrfDataHandler extends HiddenDataHandler
{
  protected $_sessionSecret;
  protected $_formSecret;
  protected $_value;

  //Total number of minutes this csrf will be valid for, null for unlimited time
  protected $_expiryMins;

  public function __construct($formSecret, $sessionSecret, ?int $expiryMinutes)
  {
    $this->setFormSecret($formSecret);
    $this->setSessionSecret($sessionSecret);
    $this->_expiryMins = $expiryMinutes;
  }

  public function applyNewToken()
  {
    $this->setValue($this->getDefaultValue());
    return $this;
  }

  public function getDefaultValue()
  {
    if($this->_defaultValue === null)
    {
      $this->_defaultValue = static::generateHash($this->_generatePassword(), $this->_expiryMins === null ? 0 : time());
    }
    return $this->_defaultValue;
  }

  public static function generateHash(string $password, $timestamp = 0): string
  {
    return md5($password . $timestamp) . base_convert($timestamp, 10, 36);
  }

  protected function _generatePassword()
  {
    return $this->_getFormSecret() . $this->_getSessionSecret();
  }

  /**
   * @return mixed
   */
  protected function _getFormSecret()
  {
    return $this->_formSecret;
  }

  /**
   * @param mixed $formSecret
   *
   * @return $this
   */
  public function setFormSecret($formSecret)
  {
    $this->_formSecret = $formSecret;
    return $this;
  }

  /**
   * @return mixed
   */
  protected function _getSessionSecret()
  {
    return $this->_sessionSecret;
  }

  /**
   * @param mixed $sessionSecret
   *
   * @return $this
   */
  public function setSessionSecret($sessionSecret)
  {
    $this->_sessionSecret = $sessionSecret;
    return $this;
  }

  protected function _setupValidators()
  {
    $this->addValidator(new CsrfValidator($this->_generatePassword(), $this->_expiryMins));
  }
}
