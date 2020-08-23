<?php

namespace Packaged\Tests\Form\Decorators;

use Packaged\Tests\Form\Supporting\CustomDecoratorForm;
use Packaged\Tests\Form\Supporting\CustomFormDecorator;
use PHPUnit\Framework\TestCase;

class FormCustomDecoratorTest extends TestCase
{
  public function testCustomDecorator()
  {
    $form = new CustomDecoratorForm('csrf');
    $form->email = 'test@example.com';
    $form->name = 'Mr Test';
    $form->password = 'securepassword';

    $form->setDecorator(new CustomFormDecorator());
    $output = $form->produceSafeHTML()->getContent();
    $this->assertContains('name="email"', $output);
    $this->assertContains('method="post"><div class="hidden"><input type="hidden"', $output);
    $this->assertContains('id="name-input"', $output);
    $this->assertContains('placeholder="Password" value=""', $output);
    $this->assertContains('placeholder="Email" value="test@example.com"', $output);
    $this->assertContains('placeholder="Name" value="Mr Test"', $output);
    $this->assertContains('<div class="err"></div>', $output);
    $this->assertContains('<div class="inp"><div class="p-form__input">', $output);
    $this->assertContains('<div class="lbl"><div class="p-form__label">', $output);
  }
}
