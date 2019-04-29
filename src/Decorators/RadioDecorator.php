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
          $radio = new Input();
          $radio->setId($name . Strings::pattern('-XXX-000'));
          $radio->setType('radio');
          $radio->setName($name);
          $radio->setValue($value);
          if(in_array($value, $currentValue))
          {
            $radio->setAttribute('checked', true);
          }
          $options[] = Div::create($radio, Label::create($label)->setAttribute('for', $radio->getid()))
            ->addClass('p-form--checkbox');
        }
        $input->setContent($options);
      }
    }
  }
}
