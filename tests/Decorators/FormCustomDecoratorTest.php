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
    $form->setDecorator(new CustomFormDecorator());
    $output = $form->produceSafeHTML()->getContent();
    $this->assertContains('name="email"', $output);
    $this->assertContains('method="post"><div class="hidden"><input type="hidden"', $output);
  }
}
