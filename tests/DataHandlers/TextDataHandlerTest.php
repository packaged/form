<?php

namespace PackagedUi\Tests\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use PackagedUi\Form\DataHandlers\TextDataHandler;
use PackagedUi\Form\Decorators\InputDecorator;
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
      '<div class="form-group"><input type="hidden" name="text" /></div>',
      $text->getDecorator()->render()
    );
  }
}
