<?php
namespace Packaged\Form\Decorators;

use Packaged\Form\DataHandlers\EnumDataHandler;
use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Ui\Html\HtmlElement;

class RadioDecorator extends AbstractDataHandlerDecorator
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
          if(!is_array($currentValue))
          {
            $currentValue = [$currentValue];
          }
          $checkbox = new Input();
          $checkbox->setType('radio');
          $checkbox->setName($name);
          $checkbox->setValue($value);
          if(in_array($value, $currentValue))
          {
            $checkbox->setAttribute('checked', true);
          }
          $options[] = Div::create($checkbox, $label)->addClass('p-form--checkbox');
        }
        $input->setContent($options);
      }
    }
  }
}
