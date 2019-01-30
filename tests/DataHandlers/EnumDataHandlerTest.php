<?php

namespace PackagedUi\Tests\Form\DataHandlers;

use PackagedUi\Form\DataHandlers\EnumDataHandler;
use PHPUnit\Framework\TestCase;

class EnumDataHandlerTest extends TestCase
{
  public function testGetElement()
  {
    $ele = new EnumDataHandler();
    $this->assertEquals('<select></select>', $ele->getDecorator()->render());

    $ele->setOptions(['a' => 'one', 'b' => 'two']);
    $this->assertEquals(
      '<select><option value="a">one</option><option value="b">two</option></select>',
      $ele->getDecorator()->render()
    );

    $ele->setValue('b');
    $this->assertEquals(
      '<select><option value="a">one</option><option value="b" selected="selected">two</option></select>',
      $ele->getDecorator()->render()
    );

    $this->assertFalse($ele->isValidValue('c'));
    $ele->addOption('c', "three");
    $this->assertTrue($ele->isValidValue('c'));

    $this->assertEquals(
      '<select><option value="a">one</option><option value="b" selected="selected">two</option><option value="c">three</option></select>',
      $ele->getDecorator()->render()
    );

    $this->assertFalse($ele->isValidValue('d'));

    $ele->getDecorator()->setId('mySelect');
    $this->assertEquals(
      '<select id="mySelect"><option value="a">one</option><option value="b" selected="selected">two</option><option value="c">three</option></select>',
      $ele->getDecorator()->render()
    );
  }
}
