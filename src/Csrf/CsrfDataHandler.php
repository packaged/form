<?php
namespace PackagedUi\Form\Csrf;

use PackagedUi\Form\DataHandlers\AbstractDataHandler;
use PackagedUi\Form\Decorators\HiddenInputDecorator;
use PackagedUi\Form\Decorators\Interfaces\DataHandlerDecorator;

class CsrfDataHandler extends AbstractDataHandler
{
  protected $_sessionSecret;
  protected $_formSecret;
  protected $_value;

  const ERR_INVALID = 'Invalid or missing CSRF token';

  public function __construct($formSecret, $sessionSecret)
  {
    $this->setFormSecret($formSecret);
    $this->setSessionSecret($sessionSecret);
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
      $this->_defaultValue = password_hash($this->_generatePassword(), PASSWORD_DEFAULT);
    }
    return $this->_defaultValue;
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

  protected function _setupValidator()
  {
    $this->addValidator(new CsrfValidator($this->_generatePassword()));
  }

  protected function _defaultDecorator(): DataHandlerDecorator
  {
    $decorator = new HiddenInputDecorator();
    $decorator->setHandler($this);
    return $decorator;
  }
}
