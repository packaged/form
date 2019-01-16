<?php
namespace PackagedUi\Form\FDH;

use Packaged\Glimpse\Core\HtmlTag;
use PackagedUi\Form\DataHandlerDecorator;
use PackagedUi\Form\Decorators\InputDecorator;
use PackagedUi\Form\FormDataHandler;

abstract class AbstractFDH implements FormDataHandler
{
  protected $_value;

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

  protected $_element;

  public function getDefaultDecorator(): DataHandlerDecorator
  {
    return new InputDecorator();
  }

  public function getElement(DataHandlerDecorator $decorator = null, array $options = null): HtmlTag
  {
    if($decorator == null)
    {
      $decorator = $this->getDefaultDecorator();
    }
    return $decorator->buildElement($this, $options);
  }

}
