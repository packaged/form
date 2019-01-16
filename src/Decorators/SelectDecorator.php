<?php
namespace PackagedUi\Form\Decorators;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Form\Option;
use Packaged\Glimpse\Tags\Form\Select;
use PackagedUi\Form\DataHandlerDecorator;
use PackagedUi\Form\FDH\EnumFDH;
use PackagedUi\Form\FormDataHandler;

class SelectDecorator implements DataHandlerDecorator
{
  public function buildElement(FormDataHandler $handler, array $options = null): HtmlTag
  {
    $element = new Select();
    if($handler instanceof EnumFDH)
    {
      foreach($handler->getOptions() as $value => $key)
      {
        $option = new Option($key, $value);
        if($value == $handler->getValue())
        {
          $option->setAttribute('selected', 'selected');
        }
        $element->appendContent($option);
      }
    }
    return $element;
  }

}
