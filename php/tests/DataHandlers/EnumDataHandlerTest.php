<?php

namespace Packaged\Tests\Form\DataHandlers;

use Packaged\Form\DataHandlers\EnumDataHandler;
use Packaged\Form\DataHandlers\MultiValueEnumDataHandler;
use PHPUnit\Framework\TestCase;

class EnumDataHandlerTest extends TestCase
{
  public function testCombined()
  {
    $ele = new EnumDataHandler();
    self::assertEquals(
      '<select></select>',
      $ele->getInput()->render()
    );

    $ele = new EnumDataHandler();
    $ele->setOptions(['a' => 'one', 'b' => 'two']);
    self::assertFalse($ele->isValid());
    self::assertEquals(
      '<select><option value="a">one</option><option value="b">two</option></select>',
      $ele->getInput()->render()
    );

    $ele = new EnumDataHandler();
    $ele->setOptions(['a' => 'one', 'b' => 'two']);
    $ele->setValue('b');
    self::assertTrue($ele->isValid());
    self::assertEquals(
      '<select><option value="a">one</option><option value="b" selected>two</option></select>',
      $ele->getInput()->render()
    );

    $ele = new EnumDataHandler();
    $ele->setOptions(['a' => 'one', 'b' => 'two']);
    $ele->setValue('b');
    self::assertFalse($ele->isValidValue('c'));
    $ele->addOption('c', "three");
    self::assertTrue($ele->isValidValue('c'));

    self::assertEquals(
      '<select><option value="a">one</option><option value="b" selected>two</option><option value="c">three</option></select>',
      $ele->getInput()->render()
    );

    self::assertFalse($ele->isValidValue('d'));

    self::assertEquals(
      '<select><option value="a">one</option><option value="b" selected>two</option><option value="c">three</option></select>',
      $ele->getInput()->render()
    );
  }

  public function testSplit()
  {
    $h = new EnumDataHandler();
    $h->styleSplit();
    $h->setName('mychoice');
    $h->setLabel('Select One');
    $h->setOptions(['one' => 'First', 'two' => 'Second', 'three' => 'Third']);

    self::assertMatchesRegularExpression(
      '~<div class="p-form__labeled-input"><input type="radio" name="mychoice" id="(mychoice-.+)" value="one" /><label for="\1">First</label></div><div class="p-form__labeled-input"><input type="radio" name="mychoice" id="(mychoice-.+)" value="two" /><label for="\2">Second</label></div><div class="p-form__labeled-input"><input type="radio" name="mychoice" id="(mychoice-.+)" value="three" /><label for="\3">Third</label></div>~',
      $h->getInput()->render()
    );

    $h = new EnumDataHandler();
    $h->styleSplit();
    $h->setName('mychoice');
    $h->setLabel('Select One');
    $h->setOptions(['one' => 'First', 'two' => 'Second', 'three' => 'Third']);
    $h->setValue('one');

    self::assertMatchesRegularExpression(
      '~<div class="p-form__labeled-input"><input type="radio" name="mychoice" id="(mychoice-.+)" value="one" checked /><label for="\1">First</label></div><div class="p-form__labeled-input"><input type="radio" name="mychoice" id="(mychoice-.+)" value="two" /><label for="\2">Second</label></div><div class="p-form__labeled-input"><input type="radio" name="mychoice" id="(mychoice-.+)" value="three" /><label for="\3">Third</label></div>~',
      $h->getInput()->render()
    );
  }

  public function testMultiEnum()
  {
    $h = new MultiValueEnumDataHandler();
    $h->styleSplit();
    $h->setName('mychoice');
    $h->setLabel('Select One');
    $h->setOptions(['one' => 'First', 'two' => 'Second', 'three' => 'Third']);
    $h->validate();
    self::assertMatchesRegularExpression(
      '~<div class="p-form__labeled-input"><input type="checkbox" name="mychoice\[\]" id="(mychoice-.+)" value="one" /><label for="\1">First</label></div><div class="p-form__labeled-input"><input type="checkbox" name="mychoice\[\]" id="(mychoice-.+)" value="two" /><label for="\2">Second</label></div><div class="p-form__labeled-input"><input type="checkbox" name="mychoice\[\]" id="(mychoice-.+)" value="three" /><label for="\3">Third</label></div>~',
      $h->getInput()->render()
    );

    $h = new MultiValueEnumDataHandler();
    $h->setName('mychoice');
    $h->setLabel('Select One');
    $h->setOptions(['one' => 'First', 'two' => 'Second', 'three' => 'Third']);
    $h->setValue('one');
    $h->validate();
    self::assertMatchesRegularExpression(
      '~<select name="mychoice\[\]" id="mychoice-.+" multiple><option value="one" selected>First</option><option value="two">Second</option><option value="three">Third</option></select>~',
      $h->getInput()->render()
    );

    $h = new MultiValueEnumDataHandler();
    $h->setName('mychoice');
    $h->setLabel('Select One');
    $h->setOptions(['one' => 'First', 'two' => 'Second', 'three' => 'Third']);
    $h->setValue(['one', 'three']);
    $h->validate();
    self::assertMatchesRegularExpression(
      '~<select name="mychoice\[\]" id="mychoice-.+" multiple><option value="one" selected>First</option><option value="two">Second</option><option value="three" selected>Third</option></select>~',
      $h->getInput()->render()
    );
  }
}
