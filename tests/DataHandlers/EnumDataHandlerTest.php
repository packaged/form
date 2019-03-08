<?php

namespace Packaged\Tests\Form\DataHandlers;

use Packaged\Form\DataHandlers\EnumDataHandler;
use PHPUnit\Framework\TestCase;

class EnumDataHandlerTest extends TestCase
{
  public function testGetElement()
  {
    $ele = new EnumDataHandler();
    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--input"><select></select></div></div>',
      $ele->getDecorator()->render()
    );

    $ele->setOptions(['a' => 'one', 'b' => 'two']);
    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--input"><select><option value="a">one</option><option value="b">two</option></select></div></div>',
      $ele->getDecorator()->render()
    );

    $ele->setValue('b');
    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--input"><select><option value="a">one</option><option value="b" selected="selected">two</option></select></div></div>',
      $ele->getDecorator()->render()
    );

    $this->assertFalse($ele->isValidValue('c'));
    $ele->addOption('c', "three");
    $this->assertTrue($ele->isValidValue('c'));

    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--input"><select><option value="a">one</option><option value="b" selected="selected">two</option><option value="c">three</option></select></div></div>',
      $ele->getDecorator()->render()
    );

    $this->assertFalse($ele->isValidValue('d'));

    $ele->getDecorator()->setId('mySelect');
    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--input"><select id="mySelect"><option value="a">one</option><option value="b" selected="selected">two</option><option value="c">three</option></select></div></div>',
      $ele->getDecorator()->render()
    );
  }
}
