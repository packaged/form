<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Glimpse\Core\AbstractContainerTag;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Glimpse\Tags\Form\Label;
use Packaged\Helpers\ValueAs;
use Packaged\Ui\Html\HtmlElement;

class BooleanDataHandler extends AbstractDataHandler
{
  public function formatValue($value)
  {
    return ValueAs::bool($value);
  }

  protected function _setupValidators()
  {
  }

  protected function _generateInput(): HtmlElement
  {
    $checkbox = new Input();
    $checkbox->setType(Input::TYPE_CHECKBOX);
    $checkbox->setName($this->getName());
    $checkbox->setId($this->getId());
    $checkbox->setValue('true');
    if($this->getValue() === true)
    {
      $checkbox->setAttribute('checked', true);
    }
    return $checkbox;
  }

  public function getPlaceholder()
  {
    return $this->_placeholder;
  }

  public function showGuidance()
  {
    return !empty($this->getPlaceholder());
  }

  public function wrapInput(HtmlElement $input): HtmlElement
  {
    $displayText = $this->getPlaceholder() ?: $this->getGuidance();
    if(!empty($displayText))
    {
      return AbstractContainerTag::create($input, Label::create($displayText)->setAttribute('for', $this->getId()));
    }
    return parent::wrapInput($input);
  }
}
