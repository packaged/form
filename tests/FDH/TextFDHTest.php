<?php

namespace PackagedUi\Tests\Form\FDH;

use PackagedUi\Form\FDH\TextFDH;
use PHPUnit\Framework\TestCase;
use stdClass;

class TextFDHTest extends TestCase
{

  public function testInvalidFormatValue()
  {
    $text = new TextFDH();
    $this->expectException(\InvalidArgumentException::class);
    $text->formatValue(new stdClass());
  }

  public function testFormatValue()
  {
    $text = new TextFDH();
    $text->setValueFormatted(true);
    $this->assertEquals('true', $text->getValue());
    $text->setValueFormatted(false);
    $this->assertEquals('false', $text->getValue());
  }
}
