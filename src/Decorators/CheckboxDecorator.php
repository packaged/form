<?php
namespace Packaged\Form\Decorators;

use Packaged\Form\DataHandlers\EnumDataHandler;
use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Ui\Html\HtmlElement;

class CheckboxDecorator extends AbstractDataHandlerDecorator
{
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
          $options[] = $this->_getCheckbox($name . '[]', $value, $label, $currentValue);
        }
        $input->setContent($options);
      }
      else
      {
        $input->setContent(
          $this->_getCheckbox($name, 'true', $this->_handler->getLabel(), $currentValue ? 'true' : 'false')
        );
      }
    }
  }

  protected function _formatElements(HtmlTag $input, ?HtmlTag $label, ?HtmlTag $errors)
  {
    $elements = parent::_formatElements($input, $label, $errors);
    if(!($this->_handler instanceof EnumDataHandler))
    {
      $elements = ['input' => $elements['input'], 'errors' => $elements['errors']];
    }
    return $elements;
  }

  private function _getCheckbox($name, $value, $text, $currentValue)
  {
    if(!is_array($currentValue))
    {
      $currentValue = [$currentValue];
    }
    $checkbox = new Input();
    $checkbox->setType('checkbox');
    $checkbox->setName($name);
    $checkbox->setValue($value);
    if(in_array($value, $currentValue))
    {
      $checkbox->setAttribute('checked', true);
    }
    return Div::create($checkbox, $text)->addClass('p-form--checkbox');
  }
}
