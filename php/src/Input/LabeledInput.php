<?php
namespace Packaged\Form\Input;

use Packaged\Glimpse\Tags\Form\Label;
use Packaged\Ui\Html\HtmlElement;
use PackagedUi\BemComponent\Bem;

class LabeledInput extends HtmlElement
{
  protected $_tag = 'div';

  protected $_input;
  protected $_label;

  public function __construct(HtmlElement $input, Label $label)
  {
    $this->_label = $label;
    $this->_input = $input;
    $this->addClass(Bem::block('p-form')->getElementName('labeled-input'));
  }

  protected function _getContentForRender()
  {
    return [$this->_input, $this->_label];
  }

}
