<?php
namespace Packaged\Form\Decorators;

use Packaged\Form\DataHandlers\EnumDataHandler;
use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Glimpse\Tags\Form\Label;
use Packaged\Helpers\Strings;
use Packaged\Ui\Html\HtmlElement;
use function in_array;
use function is_array;

class CheckboxDecorator extends AbstractDataHandlerDecorator
{
  protected $_required = false;

  /**
   * @return bool
   */
  public function isRequired(): bool
  {
    return $this->_required;
  }

  /**
   * @param bool $required
   *
   * @return $this
   */
  public function setRequired(bool $required)
  {
    $this->_required = $required;
    return $this;
  }

  protected function _initInputElement(): HtmlTag
  {
    return Div::create();
  }

  protected function _configureInputElement(HtmlElement $input)
  {
    if($input instanceof HtmlTag)
    {
      $name = $this->_handler->getName();
      $currentValue = $this->_handler->getValue();
      if($this->_handler instanceof EnumDataHandler)
      {
        $options = [];
        foreach($this->_handler->getOptions() as $value => $label)
        {
          $option = $this->_getCheckbox(
            $name . Strings::pattern('-XXX-000'),
            $name . '[]',
            $value,
            $currentValue
          );
          $options[] = $this->_getContainer($option, $label);
        }
        $input->setContent($options);
      }
      else
      {
        $checkbox = $this->_getCheckbox(
          $name . Strings::pattern('-XXX-000'),
          $name,
          'true',
          $currentValue ? 'true' : 'false'
        );
        if($this->_required)
        {
          $checkbox->setAttribute('required', true);
        }
        $input->setContent($this->_getContainer($checkbox, $this->_handler->getLabel()));
      }
    }
  }

  private function _getCheckbox($id, $name, $value, $currentValue)
  {
    if(!is_array($currentValue))
    {
      $currentValue = [$currentValue];
    }
    $checkbox = new Input();
    $checkbox->setId($id);
    $checkbox->setType('checkbox');
    $checkbox->setName($name);
    $checkbox->setValue($value);
    if(in_array($value, $currentValue))
    {
      $checkbox->setAttribute('checked', true);
    }
    return $checkbox;
  }

  private function _getContainer(Input $checkbox, $label)
  {
    return Div::create(
      $checkbox,
      Label::create($label)->setAttribute('for', $checkbox->getId())
    )->addClass('p-form--checkbox');
  }

  protected function _formatElements(HtmlTag $input, ?HtmlTag $label, ?HtmlTag $errors)
  {
    if(!($this->_handler instanceof EnumDataHandler))
    {
      $this->_elementOrder = [self::INPUT, self::ERRORS];
    }
    return parent::_formatElements($input, $label, $errors);
  }
}
