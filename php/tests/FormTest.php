<?php

namespace Packaged\Tests\Form;

use Packaged\Form\DataHandlers\TextDataHandler;
use Packaged\Tests\Form\Supporting\CustomDecoratorForm;
use Packaged\Tests\Form\Supporting\EmptyForm;
use Packaged\Tests\Form\Supporting\TestForm;
use Packaged\Validate\Validators\StringValidator;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
  public function testProperties()
  {
    $form = new TestForm();
    self::assertTrue(isset($form->text));
    self::assertNotTrue(isset($form->random));

    self::assertInstanceOf(TextDataHandler::class, $form->text);
    self::assertEquals('', $form->text->getValue());
    $form->text->setValue('abc');
    self::assertEquals('abc', $form->text->getValue());
    $form->text = 'def';
    self::assertEquals('def', $form->text->getValue());

    self::assertTrue($form->isValid());

    $form->number->setValue('a');
    self::assertFalse($form->isValid());

    $errors = $form->validate();
    self::assertIsArray($errors);
    self::assertArrayHasKey('number', $errors);

    $form->number->setValue(1);
    $form->number->addValidator(new StringValidator(3, 10));
    self::assertFalse($form->isValid());

    $form->number->setValue('abcd');
    $form->number->clearValidators();
    $this->expectExceptionMessage('must be a number');
    $form->assert();
  }

  public function testHydrate()
  {
    $form = new TestForm();
    $data = ['text' => 'abc', 'number' => 1, 'readOnly' => 'testing'];
    self::assertEmpty($form->hydrate($data));
    self::assertEquals('abc', $form->text->getValue());
    self::assertEquals(1, $form->number->getValue());
    self::assertSame($data, $form->getFormData());

    $form = new TestForm();
    $result = $form->hydrate(['text' => 'abc', 'number' => 'invalid']);
    self::assertCount(1, $result);
    self::assertArrayHasKey('number', $result);
    self::assertCount(1, $result['number']);
    self::assertEquals('must be a number', $result['number'][0]->getMessage());
    self::assertEquals(null, $form->number->getValue());

    self::assertEquals('abc', $form->text->getValue());
    self::assertNull($form->number->getValue());
  }

  public function testHydrateInvalid()
  {
    $form = new TestForm();
    $form->hydrate(['text' => 'abc', 'number' => 'invalid'], true);
    self::assertEquals('invalid', $form->number->getValue());
  }

  public function testRender()
  {
    $form = new CustomDecoratorForm('test');
    $form->setId('vbn');
    self::assertEquals(
      '<form class="p-form" id="vbn" method="post">....</form>',
      $form->render()
    );
  }

  public function testAction()
  {
    $form = new EmptyForm();
    self::assertEquals('<form class="p-form" method="post"></form>', $form->render());

    $form->setAction('/test-url');
    self::assertEquals('<form class="p-form" method="post" action="/test-url"></form>', $form->render());

    $form->setMethod('get');
    self::assertEquals('<form class="p-form" method="get" action="/test-url"></form>', $form->render());
  }
}
