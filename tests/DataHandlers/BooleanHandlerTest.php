<?php

namespace Packaged\Tests\Form\DataHandlers;

use Packaged\Form\DataHandlers\BooleanDataHandler;
use Packaged\Form\Decorators\CheckboxDecorator;
use PHPUnit\Framework\TestCase;

class BooleanHandlerTest extends TestCase
{
  public function testCheckbox()
  {
    $h = new BooleanDataHandler();
    /** @var CheckboxDecorator $decorator */
    $decorator = $h->getDecorator();
    $h->setName('mychoice');
    $h->setValue('sgfsafhasdg');
    $this->assertFalse($h->isValid());
    $this->assertFalse($decorator->isRequired());

    $h->setValueFormatted('false');
    $h->setLabel('Do You Agree?');
    $this->assertTrue($h->isValid());

    $this->assertRegExp(
      '~<div class="p-form__field"><div class="p-form__input"><div><div class="p-form__checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice" value="true" /><label for="\1">Do You Agree\?</label></div></div></div></div>~',
      $decorator->render()
    );

    $h->setValueFormatted('yes');
    $this->assertTrue($h->isValid());
    $this->assertRegExp(
      '~<div class="p-form__field"><div class="p-form__input"><div><div class="p-form__checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice" value="true" checked /><label for="\1">Do You Agree\?</label></div></div></div></div>~',
      $decorator->render()
    );

    $decorator->setRequired(true);
    $this->assertTrue($decorator->isRequired());
    $this->assertRegExp(
      '~<div class="p-form__field"><div class="p-form__input"><div><div class="p-form__checkbox"><input type="checkbox" id="(mychoice-...-...)" name="mychoice" value="true" checked required /><label for="\1">Do You Agree\?</label></div></div></div></div>~',
      $decorator->render()
    );
  }
}
