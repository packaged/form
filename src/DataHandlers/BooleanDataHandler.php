<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Glimpse\Core\AbstractContainerTag;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Glimpse\Tags\Form\Label;
use Packaged\Helpers\ValueAs;
use Packaged\Ui\Html\HtmlElement;
use Packaged\Validate\Validators\BoolValidator;

class BooleanDataHandler extends AbstractDataHandler
{
  public function formatValue($value)
  {
    return parent::formatValue(ValueAs::bool($value));
  }

  protected function _setupValidator()
  {
    $this->addValidator(new BoolValidator());
  }

  protected function _generateInput(): HtmlElement
  {
    $checkbox = new Input();
    $checkbox->setId($this->getId());
    $checkbox->setType(Input::TYPE_CHECKBOX);
    $checkbox->setName($this->getName());
    $checkbox->setValue('true');
    if(($this->getValue() ?? $this->getDefaultValue()) === true)
    {
      $checkbox->setAttribute('checked', true);
    }
    return $checkbox;
  }

  public function wrapInput(HtmlElement $input): HtmlElement
  {
    $placeholder = $this->getPlaceholder();
    if(!empty($placeholder))
    {
      return AbstractContainerTag::create($input, Label::create($placeholder)->setAttribute('for', $this->getId()));
    }
    return parent::wrapInput($input);
  }

}
