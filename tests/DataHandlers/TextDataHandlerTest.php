<?php

namespace PackagedUi\Tests\Form\DataHandlers;

use PackagedUi\Form\DataHandlers\TextDataHandler;
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
}
