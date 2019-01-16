<?php
namespace PackagedUi\Form\FDH;

use Packaged\Glimpse\Core\HtmlTag;
use PackagedUi\Form\DataHandlerDecorator;
use PackagedUi\Form\Decorators\InputDecorator;
use PackagedUi\Form\FormDataHandler;

abstract class AbstractFDH implements FormDataHandler
{
  protected $_value;
  /** @var DataHandlerDecorator */
  protected $_decorator;

  /**
   * @return mixed
   */
  public function getValue()
  {
    return $this->_value;
  }

  /**
   * @param mixed $value
   *
   * @return FormDataHandler
   */
  public function setValue($value)
  {
    $this->_value = $value;
    return $this;
  }

  /**
   * Validate a value against the current data handler
   *
   * @param $value
   *
   * @return bool
   */
  public function isValidValue($value): bool
  {
    try
    {
      $this->validate($value);
    }
    catch(\Exception $e)
    {
      return false;
    }
    return true;
  }

  /**
   * Validate the currently set value
   *
   * @return bool
   */
  public function isValid(): bool
  {
    return $this->isValidValue($this->getValue());
  }

  public function validate($value)
  {
  }

  /**
   * @param DataHandlerDecorator $decorator
   *
   * @return $this
   */
  public function setDecorator(DataHandlerDecorator $decorator)
  {
    $this->_decorator = $decorator;
    return $this;
  }

  public function getDecorator(): DataHandlerDecorator
  {
    return $this->_decorator ?? $this->_defaultDecorator();
  }

  protected function _defaultDecorator(): DataHandlerDecorator
  {
    return new InputDecorator();
  }

  public function getElement(DataHandlerDecorator $decorator = null, array $options = null): HtmlTag
  {
    if($decorator == null)
    {
      $decorator = $this->getDecorator();
    }
    return $decorator->buildElement($this, $options);
  }

}
