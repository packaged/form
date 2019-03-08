<?php

namespace Packaged\Tests\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Form\DataHandlers\TextDataHandler;
use Packaged\Form\Decorators\InputDecorator;
use PHPUnit\Framework\TestCase;
use stdClass;

class TextDataHandlerTest extends TestCase
{

  public function testInvalidFormatValue()
  {
    $text = new TextDataHandler();
    $this->expectException(\InvalidArgumentException::class);
    $text->formatValue(new stdClass());
  }

  public function testFormatValue()
  {
    $text = new TextDataHandler();
    $text->setValueFormatted(true);
    $this->assertEquals('true', $text->getValue());
    $text->setValueFormatted(false);
    $this->assertEquals('false', $text->getValue());
  }

  public function testHidden()
  {
    $dec = new InputDecorator();
    $dec->setType(Input::TYPE_HIDDEN);

    $text = new TextDataHandler();
    $text->setName('text');
    $text->setDecorator($dec);
    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--input"><input type="hidden" name="text" /></div></div>',
      $text->getDecorator()->render()
    );
  }
}
