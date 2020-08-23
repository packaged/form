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
    $this->addClass('p-form__field');
  }

  public function setValue(string $value)
  {
    $this->_value = $value;
    return $this;
  }

  protected function _getValue()
  {
    return $this->_value;
  }

  protected function _getContentForRender()
  {
    return Div::create($this->_input())->addClass('p-form__submit');
  }

  protected function _input()
  {
    return Input::create()->setType(Input::TYPE_SUBMIT)->setValue($this->_getValue());
  }

  protected function _prepareForProduce(): HtmlElement
  {
    return parent::_prepareForProduce();
  }
}
