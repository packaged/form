<?php

namespace PackagedUi\Tests\Form;

use PackagedUi\Form\DataHandlers\TextDataHandler;
use PackagedUi\Tests\Form\Supporting\DataHandlers\TestIntegerDataHandler;
use PackagedUi\Tests\Form\Supporting\TestForm;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
  public function testProperties()
  {
    $form = new TestForm();
    $this->assertTrue(isset($form->text));
    $this->assertNotTrue(isset($form->random));

    $this->assertInstanceOf(TextDataHandler::class, $form->text);
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

    $this->expectExceptionMessage(TestIntegerDataHandler::ERR_INVALID_NUMBER);
    $form->validate();
  }

  public function testHydrate()
  {
    $form = new TestForm();
    $data = ['text' => 'abc', 'number' => 1];
    $this->assertEmpty($form->hydrate($data));
    $this->assertEquals('abc', $form->text->getValue());
    $this->assertEquals(1, $form->number->getValue());
    $this->assertSame($data, $form->getFormData());

    $form = new TestForm();
    $result = $form->hydrate(['text' => 'abc', 'number' => 'invalid']);
    $this->assertCount(1, $result);
    $this->assertArrayHasKey('number', $result);
    $this->assertEquals(TestIntegerDataHandler::ERR_INVALID_NUMBER, $result['number']);

    $this->assertEquals('abc', $form->text->getValue());
    $this->assertNull($form->number->getValue());
  }

  public function testRender()
  {
    $form = new TestForm();
    $form->getDecorator()->setId('vbn');
    $this->assertRegExp(
      '/<form id="vbn" method="POST" action="\/test"><div class="form-group"><label for="text-(...)">Text<\/label><input type="text" name="text" id="text-\1" \/><\/div><div class="form-group"><label for="number-(...)">Number<\/label><input type="number" name="number" id="number-\2" \/><\/div><\/form>/',
      $form->render()
    );

    $form->number = 4;
    $form->text = 'abc';
    $this->assertRegExp(
      '/<form id="vbn" method="POST" action="\/test"><div class="form-group"><label for="text-(...)">Text<\/label><input type="text" name="text" value="abc" id="text-\1" \/><\/div><div class="form-group"><label for="number-(...)">Number<\/label><input type="number" name="number" value="4" id="number-\2" \/><\/div><\/form>/',
      $form->render()
    );

    $form->text->getDecorator()->setId('myInput');
    $this->assertRegExp(
      '/<form id="vbn" method="POST" action="\/test"><div class="form-group"><label for="myInput">Text<\/label><input type="text" id="myInput" name="text" value="abc" \/><\/div><div class="form-group"><label for="number-(...)">Number<\/label><input type="number" name="number" value="4" id="number-\1" \/><\/div><\/form>/',
      $form->render()
    );
  }
}
