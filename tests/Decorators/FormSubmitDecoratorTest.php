<?php

namespace Packaged\Tests\Form\Decorators;

use Packaged\Form\Decorators\FormSubmitDecorator;
use PHPUnit\Framework\TestCase;

class FormSubmitDecoratorTest extends TestCase
{
  public function testSubmitDecorator()
  {
    $dec = new FormSubmitDecorator();
    $this->assertEquals(
      '<div class="p-form__field"><div class="p-form__submit"><input type="submit" value="Submit" /></div></div>',
      $dec->render()
    );
    $dec->setValue('Press Here');
    $this->assertEquals(
      '<div class="p-form__field"><div class="p-form__submit"><input type="submit" value="Press Here" /></div></div>',
      $dec->render()
    );
  }
}
