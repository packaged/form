<?php
namespace Packaged\Form\Input;

use Packaged\Ui\Html\HtmlElement;

class MultiInputContainer extends HtmlElement
{
  /** @var HtmlElement[] */
  protected $_inputs = [];

  public function addInput(HtmlElement $input)
  {
    $this->_inputs[] = $input;
    return $this;
  }

  /**
   * @return HtmlElement[]
   */
  public function getInputs()
  {
    return $this->_inputs;
  }

  protected function _getContentForRender()
  {
    return $this->_inputs;
  }

}
