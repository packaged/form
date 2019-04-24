<?php

namespace Packaged\Tests\Form\DataHandlers;

use InvalidArgumentException;
use Packaged\Form\DataHandlers\TextDataHandler;
use Packaged\Form\Decorators\InputDecorator;
use Packaged\Glimpse\Tags\Form\Input;
use PHPUnit\Framework\TestCase;
use stdClass;

class TextDataHandlerTest extends TestCase
{
  public function testTextHandler()
  {
    $text = new TextDataHandler();
    $text->setName('test');
    $text->setValue('my text');
    $this->assertRegExp(
      '~<div class="p-form-field"><div class="p-form--label"><label for="test-(...)">Test</label></div><div class="p-form--input"><input type="text" id="test-\1" name="test" placeholder="Test" value="my text" /></div></div>~',
      $text->getDecorator()->render()
    );
    $text->getDecorator()->getLabel()->setContent('changed label');
    $this->assertRegExp(
      '~<div class="p-form-field"><div class="p-form--label"><label for="test-(...)">changed label</label></div><div class="p-form--input"><input type="text" id="test-\1" name="test" placeholder="Test" value="my text" /></div></div>~',
      $text->getDecorator()->render()
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
    $text->setValueFormatted(true);
    $this->assertEquals('true', $text->getValue());
    $text->setValueFormatted(false);
    $this->assertEquals('false', $text->getValue());
  }

  public function testHidden()
  {
    $dec = new InputDecorator();
    $dec->getInput()->setAttribute('type', Input::TYPE_HIDDEN);

    $text = new TextDataHandler();
    $text->setName('text');
    $text->setDecorator($dec);
    $this->assertRegExp(
      '~<div class="p-form-field"><div class="p-form--label"><label for="(text-...)">Text</label></div><div class="p-form--input"><input type="hidden" id="\1" name="text" /></div></div>~',
      $text->getDecorator()->render()
    );
  }
}
