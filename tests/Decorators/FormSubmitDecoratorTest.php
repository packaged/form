<?php

namespace Packaged\Tests\Form\Decorators;

use Packaged\Form\Form\FormSubmitDecorator;
use PHPUnit\Framework\TestCase;

class FormSubmitDecoratorTest extends TestCase
{
  public function testSubmitDecorator()
  {
    $dec = new FormSubmitDecorator();
    $this->assertEquals('<div class="p-form-field"><input type="submit" value="Submit" /></div>', $dec->render());
    $dec->setValue('Press Here');
    $this->assertEquals('<div class="p-form-field"><input type="submit" value="Press Here" /></div>', $dec->render());
  }
}
