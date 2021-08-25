<?php

namespace Packaged\Tests\Form\DataHandlers;

use InvalidArgumentException;
use Packaged\Form\DataHandlers\HiddenDataHandler;
use Packaged\Form\DataHandlers\TextDataHandler;
use PHPUnit\Framework\TestCase;
use stdClass;

class TextDataHandlerTest extends TestCase
{
  public function testTextHandler()
  {
    $text = new TextDataHandler();
    $text->setName('test');
    $text->setValue('my text');
    self::assertMatchesRegularExpression(
      '~<input type="text" name="test" id="test-.+" value="my text" placeholder="Test" />~',
      $text->getInput()->render()
    );
  }

  public function testInvalidFormatValue()
  {
    $text = new TextDataHandler();
    $this->expectException(InvalidArgumentException::class);
    $text->formatValue(new stdClass());
  }

  public function testFormatValue()
  {
    $text = new TextDataHandler();
    $text->setValue(true);
    self::assertEquals('true', $text->getValue());
    $text->setValue(false);
    self::assertEquals('false', $text->getValue());
  }

  public function testHidden()
  {
    $text = new HiddenDataHandler();
    $text->setName('text');
    self::assertMatchesRegularExpression(
      '~<input type="hidden" name="text" id="text-.+" />~',
      $text->getInput()->render()
    );
  }
}
