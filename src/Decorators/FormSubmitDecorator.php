<?php
namespace Packaged\Form\Decorators;

use Packaged\Form\Decorators\Interfaces\Decorator;
use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Ui\Html\TemplatedHtmlElement;

class FormSubmitDecorator extends TemplatedHtmlElement implements Decorator
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
}
