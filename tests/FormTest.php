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

  public function testHydrate()
  {
    $form = new TestForm();
    $this->assertEmpty($form->hydrate(['text' => 'abc', 'number' => 1]));
    $this->assertEquals('abc', $form->text->getValue());
    $this->assertEquals(1, $form->number->getValue());

    $form = new TestForm();
    $result = $form->hydrate(['text' => 'abc', 'number' => 'invalid']);
    $this->assertCount(1, $result);
    $this->assertArrayHasKey('number', $result);
    $this->assertEquals(TestIntegerFDH::ERR_INVALID_NUMBER, $result['number']);

    $this->assertEquals('abc', $form->text->getValue());
    $this->assertNull($form->number->getValue());
  }
}
