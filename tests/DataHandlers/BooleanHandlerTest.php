<?php

namespace Packaged\Tests\Form\DataHandlers;

use Packaged\Form\DataHandlers\BooleanDataHandler;
use PHPUnit\Framework\TestCase;

class BooleanHandlerTest extends TestCase
{
  public function testCheckbox()
  {
    $h = new BooleanDataHandler();
    $h->setName('mychoice');
    $h->setValue('sgfsafhasdg');
    $this->assertFalse($h->isValid());

    $h->setValueFormatted('false');
    $h->setLabel('Do You Agree?');
    $this->assertTrue($h->isValid());

    $this->assertRegExp(
      '~<div class="p-form-field"><div class="p-form--input"><div><div class="p-form--checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice" value="true" /><label for="\1">Do You Agree\?</label></div></div></div></div>~',
      $h->getDecorator()->render()
    );

    $h->setValueFormatted('yes');
    $this->assertTrue($h->isValid());
    $this->assertRegExp(
      '~<div class="p-form-field"><div class="p-form--input"><div><div class="p-form--checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice" value="true" checked /><label for="\1">Do You Agree\?</label></div></div></div></div>~',
      $h->getDecorator()->render()
    );
  }
}
