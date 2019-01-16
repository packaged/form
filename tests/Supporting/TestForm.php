<?php
namespace PackagedUi\Tests\Form\Supporting;

use PackagedUi\Form\FormDataHandlers\TextFDH;
use PackagedUi\Form\Form;
use PackagedUi\Tests\Form\Supporting\FDH\TestIntegerFDH;

class TestForm extends Form
{
  /**
   * @var TextFDH
   */
  public $text;
  /**
   * @var TestIntegerFDH
   */
  public $number;

  protected function _getAction()
  {
    return '/test';
  }

  protected function _initDataHandlers()
  {
    $this->text = new TextFDH();
    $this->number = new TestIntegerFDH();
  }
}
