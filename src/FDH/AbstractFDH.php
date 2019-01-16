<?php
namespace PackagedUi\Form\FDH;

use Packaged\Glimpse\Tags\Form\Input;
use PackagedUi\Form\FormDataHandler;

abstract class AbstractFDH implements FormDataHandler
{
  protected $_value;
  protected $_type = Input::TYPE_HIDDEN;

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
   * @return string
   */
  public function getType(): string
  {
    return $this->_type;
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
      $this->validate();
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

  public function validate()
  {
  }
}
