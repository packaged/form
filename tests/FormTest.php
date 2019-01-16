<?php

namespace PackagedUi\Tests\Form;

use PackagedUi\Form\FDH\TextFDH;
use PackagedUi\Tests\Form\Supporting\FDH\TestIntegerFDH;
use PackagedUi\Tests\Form\Supporting\TestForm;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
  public function testProperties()
  {
    $form = new TestForm();
    $this->assertTrue(isset($form->text));
    $this->assertNotTrue(isset($form->random));

    $this->assertInstanceOf(TextFDH::class, $form->text);
    $this->assertEquals('', $form->text->getValue());
    $form->text->setValue('abc');
    $this->assertEquals('abc', $form->text->getValue());
    $form->text = 'def';
    $this->assertEquals('def', $form->text->getValue());

    $this->assertTrue($form->isValid());

    $form->number->setValue('a');
    $this->assertFalse($form->isValid());

    $this->assertIsArray($form->getErrors());
    $this->assertArrayHasKey('number', $form->getErrors());

    $this->expectExceptionMessage(TestIntegerFDH::ERR_INVALID_NUMBER);
    $form->validate();
  }
}
