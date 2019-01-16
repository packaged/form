<?php
namespace PackagedUi\Form\FDH;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Glimpse\Tags\Form\Option;
use Packaged\Glimpse\Tags\Form\Select;

class EnumFDH extends AbstractFDH
{
  protected $_options = [];

  public function setOptions(array $value)
  {
    $this->_options = $value;
    return $this;
  }

  public function addOption($value, $display = null)
  {
    $this->_options[$value] = $display ?? $value;
    return $this;
  }

  public function getOptions()
  {
    return $this->_options;
  }

  public function getElement(): HtmlTag
  {
    $element = new Select();
    foreach($this->getOptions() as $value => $key)
    {
      $option = new Option($key, $value);
      if($value == $this->getValue())
      {
        $option->setAttribute('selected', 'selected');
      }
      $element->appendContent($option);
    }
    return $element;
  }

}
