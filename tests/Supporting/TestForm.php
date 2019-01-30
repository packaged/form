<?php
namespace PackagedUi\Tests\Form\Supporting;

use PackagedUi\Form\DataHandlers\TextDataHandler;
use PackagedUi\Form\Form\Form;
use PackagedUi\Tests\Form\Supporting\DataHandlers\TestIntegerDataHandler;

class TestForm extends Form
{
  /**
   * @var TextDataHandler
   */
  public $text;
  /**
   * @var TestIntegerDataHandler
   */
  public $number;

  public function getAction()
  {
    return '/test';
  }

  protected function _initDataHandlers()
  {
    $this->text = new TextDataHandler();
    $this->number = new TestIntegerDataHandler();
  }
}
