<?php

namespace Packaged\Tests\Form\DataHandlers;

use InvalidArgumentException;
use Packaged\Form\DataHandlers\HiddenDataHandler;
use Packaged\Form\DataHandlers\MultiLineTextDataHandler;
use PHPUnit\Framework\TestCase;
use stdClass;

class MultiLineTextDataHandlerTest extends TestCase
{
  public function testTextAreaHandler()
  {
    $text = new MultiLineTextDataHandler();
    $text->setName('test');
    $text->setValue('my text');
    self::assertMatchesRegularExpression(
      '~<textarea name="test" id="test-.+" placeholder="Test">my text</textarea>~',
      $text->getInput()->render()
    );
  }

  public function testInvalidFormatValue()
  {
    $text = new MultiLineTextDataHandler();
    $this->expectException(InvalidArgumentException::class);
    $text->formatValue(new stdClass());
  }

  public function testFormatValue()
  {
    $text = new MultiLineTextDataHandler();
    $text->setValue(true);
    self::assertEquals('true', $text->getValue());
    $text->setValue(false);
    self::assertEquals('false', $text->getValue());
  }

  public function testSetRows()
  {
    $text = new MultiLineTextDataHandler();
    $text->setName('test');
    $text->setRows(3);
    self::assertMatchesRegularExpression(
      '~<textarea name="test" id="test-.+" placeholder="Test" rows="3"></textarea>~',
      $text->getInput()->render()
    );
    $text = new MultiLineTextDataHandler();
    $text->setName('test');
    $text->setRows(10);
    self::assertMatchesRegularExpression(
      '~<textarea name="test" id="test-.+" placeholder="Test" rows="10"></textarea>~',
      $text->getInput()->render()
    );
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
