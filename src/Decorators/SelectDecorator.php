<?php
namespace PackagedUi\Form\Decorators;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Form\Option;
use Packaged\Glimpse\Tags\Form\Select;
use PackagedUi\Form\DataHandlers\EnumDataHandler;

class SelectDecorator extends AbstractDataHandlerDecorator
{
  protected function _getInputElement(): HtmlTag
  {
    $input = Select::create();
    $input->setId($this->getId());
    $input->setName($this->_handler->getName());

    $currentValue = $this->_handler->getValue();
    if($this->_handler instanceof EnumDataHandler)
    {
      foreach($this->_handler->getOptions() as $value => $key)
      {
        $option = new Option($key, $value);
        if($value == $currentValue)
        {
          $option->setAttribute('selected', 'selected');
        }
        $input->appendContent($option);
      }
    }
    return $input;
  }
}
