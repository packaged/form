<?php

namespace PackagedUi\Tests\Form\DataHandlers;

use PackagedUi\Form\DataHandlers\EnumDataHandler;
use PHPUnit\Framework\TestCase;

class EnumDataHandlerTest extends TestCase
{
  public function testGetElement()
  {
    $ele = new EnumDataHandler();
    $this->assertEquals('<select></select>', $ele->getElement()->produceSafeHTML()->getContent());

    $ele->setOptions(['a' => 'one', 'b' => 'two']);
    $this->assertEquals(
      '<select><option value="a">one</option><option value="b">two</option></select>',
      $ele->getElement()->produceSafeHTML()->getContent()
    );

    $ele->setValue('b');
    $this->assertEquals(
      '<select><option value="a">one</option><option value="b" selected="selected">two</option></select>',
      $ele->getElement()->produceSafeHTML()->getContent()

    );

    $this->assertFalse($ele->isValidValue('c'));
    $ele->addOption('c', "three");
    $this->assertTrue($ele->isValidValue('c'));

    $this->assertEquals(
      '<select><option value="a">one</option><option value="b" selected="selected">two</option><option value="c">three</option></select>',
      $ele->getElement()->produceSafeHTML()->getContent()
    );

    $this->assertFalse($ele->isValidValue('d'));
  }
}
