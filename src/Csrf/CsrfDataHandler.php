<?php
namespace PackagedUi\Form\Csrf;

use Packaged\Glimpse\Tags\Form\Input;
use PackagedUi\Form\DataHandlerDecorator;
use PackagedUi\Form\DataHandlers\AbstractDataHandler;
use PackagedUi\Form\Decorators\InputDecorator;

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
    return password_hash($this->_generatePassword(), PASSWORD_DEFAULT);
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

  public function validate($value)
  {

    if(!password_verify($this->_generatePassword(), $value))
    {
      throw new \InvalidArgumentException(self::ERR_INVALID);
    }
  }

  protected function _defaultDecorator(): DataHandlerDecorator
  {
    $decorator = new InputDecorator();
    $decorator->setType(Input::TYPE_HIDDEN);
    return $decorator;
  }

}
