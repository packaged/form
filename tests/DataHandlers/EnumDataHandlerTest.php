<?php

namespace Packaged\Tests\Form\DataHandlers;

use Packaged\Form\DataHandlers\EnumDataHandler;
use Packaged\Form\DataHandlers\MultiValueEnumDataHandler;
use Packaged\Form\Decorators\RadioDecorator;
use Packaged\Form\Decorators\SelectDecorator;
use PHPUnit\Framework\TestCase;

class EnumDataHandlerTest extends TestCase
{
  public function testGetElement()
  {
    $ele = new EnumDataHandler();
    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--label"><label></label></div><div class="p-form--input"><select></select></div></div>',
      $ele->getDecorator()->render()
    );

    $ele = new EnumDataHandler();
    $ele->setOptions(['a' => 'one', 'b' => 'two']);
    $ele->validate();
    $this->assertEquals(
      '<div class="p-form-field p-form-field--error"><div class="p-form--label"><label></label></div><div class="p-form--errors"><ul><li>not a valid value</li></ul></div><div class="p-form--input"><select><option value="a">one</option><option value="b">two</option></select></div></div>',
      $ele->getDecorator()->render()
    );

    $ele = new EnumDataHandler();
    $ele->setOptions(['a' => 'one', 'b' => 'two']);
    $ele->setValue('b');
    $ele->validate();
    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--label"><label></label></div><div class="p-form--input"><select><option value="a">one</option><option value="b" selected>two</option></select></div></div>',
      $ele->getDecorator()->render()
    );

    $ele = new EnumDataHandler();
    $ele->setOptions(['a' => 'one', 'b' => 'two']);
    $ele->setValue('b');
    $this->assertFalse($ele->isValidValue('c'));
    $ele->addOption('c', "three");
    $this->assertTrue($ele->isValidValue('c'));

    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--label"><label></label></div><div class="p-form--input"><select><option value="a">one</option><option value="b" selected>two</option><option value="c">three</option></select></div></div>',
      $ele->getDecorator()->render()
    );

    $this->assertFalse($ele->isValidValue('d'));

    /** @var SelectDecorator $dec */
    $ele->getDecorator()->getInput()->setId('mySelect');
    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--label"><label for="mySelect"></label></div><div class="p-form--input"><select id="mySelect"><option value="a">one</option><option value="b" selected>two</option><option value="c">three</option></select></div></div>',
      $ele->getDecorator()->render()
    );
  }

  public function testCheckboxEnum()
  {
    $h = new MultiValueEnumDataHandler();
    $h->setName('mychoice');
    $h->setLabel('Select One');
    $h->setOptions(['one' => 'First', 'two' => 'Second', 'three' => 'Third']);
    $h->validate();
    $this->assertRegExp(
      '~<div class="p-form-field p-form-field--error"><div class="p-form--label"><label>Select One</label></div><div class="p-form--errors"><ul><li>must be an array</li></ul></div><div class="p-form--input"><div><div class="p-form--checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice\[\]" value="one" /><label for="\1">First</label></div><div class="p-form--checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice\[\]" value="two" /><label for="\2">Second</label></div><div class="p-form--checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice\[\]" value="three" /><label for="\3">Third</label></div></div></div></div>~',
      $h->getDecorator()->render()
    );

    $h = new MultiValueEnumDataHandler();
    $h->setName('mychoice');
    $h->setLabel('Select One');
    $h->setOptions(['one' => 'First', 'two' => 'Second', 'three' => 'Third']);
    $h->setValueFormatted('one');
    $h->validate();
    $this->assertRegExp(
      '~<div class="p-form-field"><div class="p-form--label"><label>Select One</label></div><div class="p-form--input"><div><div class="p-form--checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice\[\]" value="one" checked /><label for="\1">First</label></div><div class="p-form--checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice\[\]" value="two" /><label for="\2">Second</label></div><div class="p-form--checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice\[\]" value="three" /><label for="\3">Third</label></div></div></div></div>~',
      $h->getDecorator()->render()
    );

    $h = new MultiValueEnumDataHandler();
    $h->setName('mychoice');
    $h->setLabel('Select One');
    $h->setOptions(['one' => 'First', 'two' => 'Second', 'three' => 'Third']);
    $h->setValueFormatted(['one', 'three']);
    $h->validate();
    $this->assertRegExp(
      '~<div class="p-form-field"><div class="p-form--label"><label>Select One</label></div><div class="p-form--input"><div><div class="p-form--checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice\[\]" value="one" checked /><label for="\1">First</label></div><div class="p-form--checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice\[\]" value="two" /><label for="\2">Second</label></div><div class="p-form--checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice\[\]" value="three" checked /><label for="\3">Third</label></div></div></div></div>~',
      $h->getDecorator()->render()
    );
  }

  public function testRadioEnum()
  {
    $h = new EnumDataHandler();
    $h->setName('mychoice');
    $h->setLabel('Select One');
    $h->setOptions(['one' => 'First', 'two' => 'Second', 'three' => 'Third']);
    $h->setDecorator(new RadioDecorator());
    $h->validate();

    $this->assertRegExp(
      '~<div class="p-form-field p-form-field--error"><div class="p-form--label"><label>Select One</label></div><div class="p-form--errors"><ul><li>not a valid value</li></ul></div><div class="p-form--input"><div><div class="p-form--checkbox"><input type="radio" id="(mychoice-...-...)" name="mychoice" value="one" /><label for="\1">First</label></div><div class="p-form--checkbox"><input type="radio" id="(mychoice-...-...)" name="mychoice" value="two" /><label for="\2">Second</label></div><div class="p-form--checkbox"><input type="radio" id="(mychoice-...-...)" name="mychoice" value="three" /><label for="\3">Third</label></div></div></div></div>~',
      $h->getDecorator()->render()
    );

    $h = new EnumDataHandler();
    $h->setName('mychoice');
    $h->setLabel('Select One');
    $h->setOptions(['one' => 'First', 'two' => 'Second', 'three' => 'Third']);
    $h->setDecorator(new RadioDecorator());
    $h->setValue('one');
    $h->validate();

    $this->assertRegExp(
      '~<div class="p-form-field"><div class="p-form--label"><label>Select One</label></div><div class="p-form--input"><div><div class="p-form--checkbox"><input type="radio" id="(mychoice-...-...)" name="mychoice" value="one" checked /><label for="\1">First</label></div><div class="p-form--checkbox"><input type="radio" id="(mychoice-...-...)" name="mychoice" value="two" /><label for="\2">Second</label></div><div class="p-form--checkbox"><input type="radio" id="(mychoice-...-...)" name="mychoice" value="three" /><label for="\3">Third</label></div></div></div></div>~',
      $h->getDecorator()->render()
    );
  }
}
