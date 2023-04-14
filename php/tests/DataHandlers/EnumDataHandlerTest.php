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

  public function testNonAssocArray()
  {
    $h = new EnumDataHandler();
    $h->setOptions(['1', '2', '3', '4', '5']);
    $h->setValue('1');

    self::assertEquals(
      '<select><option selected>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select>',
      $h->getInput()->render()
    );
  }

  public function testAssocArray()
  {
    $h = new EnumDataHandler();
    $h->setOptions(['1' => 'Banana', '2' => 'Apple']);
    $h->setValue('2');

    self::assertEquals(
      '<select><option value="1">Banana</option><option value="2" selected>Apple</option></select>',
      $h->getInput()->render()
    );
  }

  public function testAssocArray2()
  {
    $h = new EnumDataHandler();
    $h->setOptions(['Banana' => 'Banana', 'Apple' => 'Apple']);
    $h->setValue('Apple');

    self::assertEquals(
      '<select><option value="Banana">Banana</option><option value="Apple" selected>Apple</option></select>',
      $h->getInput()->render()
    );
  }

  public function testNonAssocArraySplit()
  {
    $h = new EnumDataHandler();
    $h->setOptions(['1', '2', '3']);
    $h->setValue('1');
    $h->styleSplit();

    self::assertMatchesRegularExpression(
      '~<div class="p-form__labeled-input"><input type="radio" id="(.+)" value="1" checked /><label for="\1">1</label></div><div class="p-form__labeled-input"><input type="radio" id="(.+)" value="2" /><label for="\2">2</label></div><div class="p-form__labeled-input"><input type="radio" id="(.+)" value="3" /><label for="\3">3</label></div>~',
      $h->getInput()->render()
    );
  }

  public function testAssocArraySplit()
  {
    $h = new EnumDataHandler();
    $h->setOptions(['1' => 'Banana', '2' => 'Apple']);
    $h->setValue('2');
    $h->styleSplit();

    self::assertMatchesRegularExpression(
      '~<div class="p-form__labeled-input"><input type="radio" id="(.+)" value="1" /><label for="\1">Banana</label></div><div class="p-form__labeled-input"><input type="radio" id="(.+)" value="2" checked /><label for="\2">Apple</label></div>~',
      $h->getInput()->render()
    );
  }

  public function testAssocArraySplit2()
  {
    $h = new EnumDataHandler();
    $h->setOptions(['Banana' => 'Banana', 'Apple' => 'Apple']);
    $h->setValue('Apple');
    $h->styleSplit();

    self::assertMatchesRegularExpression(
      '~<div class="p-form__labeled-input"><input type="radio" id="(.+)" value="Banana" /><label for="\1">Banana</label></div><div class="p-form__labeled-input"><input type="radio" id="(.+)" value="Apple" checked /><label for="\2">Apple</label></div>~',
      $h->getInput()->render()
    );
  }
}
