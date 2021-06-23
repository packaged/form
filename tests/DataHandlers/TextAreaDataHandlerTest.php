<?php

namespace Packaged\Tests\Form\DataHandlers;

use InvalidArgumentException;
use Packaged\Form\DataHandlers\HiddenDataHandler;
use Packaged\Form\DataHandlers\TextAreaDataHandler;
use Packaged\Form\DataHandlers\TextDataHandler;
use PHPUnit\Framework\TestCase;
use stdClass;

class TextAreaDataHandlerTest extends TestCase
{
  public function testTextAreaHandler()
  {
    $text = new TextAreaDataHandler();
    $text->setName('test');
    $text->setValue('my text');
    self::assertMatchesRegularExpression(
      '~<textarea name="test" id="test-.+" placeholder="Test" rows="4">my text</textarea>~',
      $text->getInput()->render()
    );
  }

  public function testInvalidFormatValue()
  {
    $text = new TextAreaDataHandler();
    $this->expectException(InvalidArgumentException::class);
    $text->formatValue(new stdClass());
  }

  public function testFormatValue()
  {
    $text = new TextAreaDataHandler();
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
