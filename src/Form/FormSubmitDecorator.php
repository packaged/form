<?php
namespace Packaged\Form\Form;

use Packaged\Form\Decorators\AbstractDecorator;
use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Ui\Html\HtmlElement;

class FormSubmitDecorator extends AbstractDecorator
{
  protected $_tag = 'div';
  private $_value = 'Submit';

  public function __construct()
  {
    $this->addClass('p-form-field');
  }

  public function setValue(string $value)
  {
    $this->_value = $value;
    return $this;
  }

  protected function _getContentForRender()
  {
    return Div::create(
      Input::create()->setType(Input::TYPE_SUBMIT)->setValue($this->_value)
    )->addClass('p-form--submit');
  }

  protected function _prepareForProduce(): HtmlElement
  {
    return parent::_prepareForProduce();
  }
}
