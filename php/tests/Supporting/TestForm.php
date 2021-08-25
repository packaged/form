<?php
namespace Packaged\Tests\Form\Supporting;

use Packaged\Form\DataHandlers\ReadOnlyDataHandler;
use Packaged\Form\DataHandlers\TextDataHandler;
use Packaged\Form\Form\Form;
use Packaged\Tests\Form\Supporting\DataHandlers\TestIntegerDataHandler;

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

  /**
   * @var ReadOnlyDataHandler
   */
  public $readOnly;

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
