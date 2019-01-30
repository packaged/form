<?php
namespace PackagedUi\Form\Decorators;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Form\Option;
use Packaged\Glimpse\Tags\Form\Select;
use PackagedUi\Form\DataHandlers\EnumDataHandler;

class SelectDecorator extends AbstractDataHandlerDecorator
{
  protected function _getInput(): HtmlTag
  {
    $element = Select::create()
      ->setId($this->getId());
    if($this->_handler instanceof EnumDataHandler)
    {
      foreach($this->_handler->getOptions() as $value => $key)
      {
        $option = new Option($key, $value);
        if($value == $this->_handler->getValue())
        {
          $option->setAttribute('selected', 'selected');
        }
        $element->appendContent($option);
      }
    }
    return $element;
  }
}
