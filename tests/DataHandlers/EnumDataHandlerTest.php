<?php

namespace Packaged\Tests\Form\DataHandlers;

use Packaged\Form\DataHandlers\EnumDataHandler;
use Packaged\Form\Decorators\CheckboxDecorator;
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

    $ele->setOptions(['a' => 'one', 'b' => 'two']);
    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--label"><label></label></div><div class="p-form--input"><select><option value="a">one</option><option value="b">two</option></select></div></div>',
      $ele->getDecorator()->render()
    );

    $ele->setValue('b');
    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--label"><label></label></div><div class="p-form--input"><select><option value="a">one</option><option value="b" selected>two</option></select></div></div>',
      $ele->getDecorator()->render()
    );

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
    $h = new EnumDataHandler();
    $h->setName('mychoice');
    $h->setLabel('Select One');
    $h->setOptions(['one' => 'First', 'two' => 'Second', 'three' => 'Third']);
    $h->setDecorator(new CheckboxDecorator());

    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--label"><label>Select One</label></div><div class="p-form--input"><div><div class="p-form--checkbox"><input type="checkbox" name="mychoice[]" value="one" />First</div><div class="p-form--checkbox"><input type="checkbox" name="mychoice[]" value="two" />Second</div><div class="p-form--checkbox"><input type="checkbox" name="mychoice[]" value="three" />Third</div></div></div></div>',
      $h->getDecorator()->render()
    );

    $h->setValue(['one', 'three']);
    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--label"><label>Select One</label></div><div class="p-form--input"><div><div class="p-form--checkbox"><input type="checkbox" name="mychoice[]" value="one" checked />First</div><div class="p-form--checkbox"><input type="checkbox" name="mychoice[]" value="two" />Second</div><div class="p-form--checkbox"><input type="checkbox" name="mychoice[]" value="three" checked />Third</div></div></div></div>',
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

    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--label"><label>Select One</label></div><div class="p-form--input"><div><div class="p-form--checkbox"><input type="radio" name="mychoice" value="one" />First</div><div class="p-form--checkbox"><input type="radio" name="mychoice" value="two" />Second</div><div class="p-form--checkbox"><input type="radio" name="mychoice" value="three" />Third</div></div></div></div>',
      $h->getDecorator()->render()
    );

    $h->setValue(['one', 'three']);
    $this->assertEquals(
      '<div class="p-form-field"><div class="p-form--label"><label>Select One</label></div><div class="p-form--input"><div><div class="p-form--checkbox"><input type="radio" name="mychoice" value="one" checked />First</div><div class="p-form--checkbox"><input type="radio" name="mychoice" value="two" />Second</div><div class="p-form--checkbox"><input type="radio" name="mychoice" value="three" checked />Third</div></div></div></div>',
      $h->getDecorator()->render()
    );
  }
}
