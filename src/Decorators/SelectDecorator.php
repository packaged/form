<?php
namespace Packaged\Form\Decorators;

use Packaged\Form\DataHandlers\EnumDataHandler;
use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Form\Option;
use Packaged\Glimpse\Tags\Form\Select;
use Packaged\Ui\Html\HtmlElement;

class SelectDecorator extends AbstractDataHandlerDecorator
{
  protected function _initInputElement(): HtmlTag
  {
    return Select::create();
  }

  protected function _configureInputElement(HtmlElement $input)
  {
    if($input instanceof Select)
    {
      $currentValue = $this->_handler->getValue();
      if($this->_handler instanceof EnumDataHandler)
      {
        $options = [];
        foreach($this->_handler->getOptions() as $value => $key)
        {
          $option = new Option($key, $value);
          if($value == $currentValue)
          {
            $option->setAttribute('selected', true);
          }
          $options[] = $option;
        }
        $input->setContent($options);
      }
    }
  }
}
