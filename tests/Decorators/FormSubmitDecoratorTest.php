<?php

namespace Packaged\Tests\Form\Decorators;

use Packaged\Form\Form\FormSubmitDecorator;
use PHPUnit\Framework\TestCase;

class FormSubmitDecoratorTest extends TestCase
{
  public function testSubmitDecorator()
  {
    $dec = new FormSubmitDecorator();
    $this->assertEquals('<div class="p-form-field"><div class="p-form--submit"><input type="submit" value="Submit" /></div></div>', $dec->render());
    $dec->setValue('Press Here');
    $this->assertEquals('<div class="p-form-field"><div class="p-form--submit"><input type="submit" value="Press Here" /></div></div>', $dec->render());
  }
}
