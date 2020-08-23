<?php

namespace Packaged\Tests\Form;

use Packaged\Form\DataHandlers\TextDataHandler;
use Packaged\Form\Decorators\ReadOnlyDecorator;
use Packaged\Tests\Form\Supporting\EmptyForm;
use Packaged\Tests\Form\Supporting\TestForm;
use Packaged\Validate\Validators\StringValidator;
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

    $errors = $form->validate();
    $this->assertIsArray($errors);
    $this->assertArrayHasKey('number', $errors);

    $form->number->setValue(1);
    $form->number->addValidator(new StringValidator(3, 10));
    $this->assertFalse($form->isValid());

    $form->number->setValue('abcd');
    $form->number->clearValidators();
    $this->expectExceptionMessage('must be a number');
    $form->assert();
  }

  public function testHydrate()
  {
    $form = new TestForm();
    $data = ['text' => 'abc', 'number' => 1, 'readOnly' => 'testing'];
    $this->assertEmpty($form->hydrate($data));
    $this->assertEquals('abc', $form->text->getValue());
    $this->assertEquals(1, $form->number->getValue());
    $this->assertSame($data, $form->getFormData());

    $form = new TestForm();
    $result = $form->hydrate(['text' => 'abc', 'number' => 'invalid']);
    $this->assertCount(1, $result);
    $this->assertArrayHasKey('number', $result);
    $this->assertCount(1, $result['number']);
    $this->assertEquals('must be a number', $result['number'][0]->getMessage());
    $this->assertEquals(null, $form->number->getValue());

    $this->assertEquals('abc', $form->text->getValue());
    $this->assertNull($form->number->getValue());
  }

  public function testGetHandlersByDecorator()
  {
    $form = new TestForm();
    $handlers = $form->getHandlersByDecorator(ReadOnlyDecorator::class);
    static::assertCount(1, $handlers);
  }

  public function testHydrateInvalid()
  {
    $form = new TestForm();
    $form->hydrate(['text' => 'abc', 'number' => 'invalid'], true);
    $this->assertEquals('invalid', $form->number->getValue());
  }

  public function testRender()
  {
    $form = new TestForm();
    $form->getDecorator()->setId('vbn');
    $this->assertRegExp(
      '~<form class="p-form" id="vbn" action="/test" method="post"><div class="p-form__field"><div class="p-form__label"><label for="text-(...)">Text</label></div><div class="p-form__input"><input type="text" id="text-\1" name="text" placeholder="Text" /></div></div><div class="p-form__field"><div class="p-form__label"><label for="number-(...)">Number</label></div><div class="p-form__input"><input type="number" id="number-\2" name="number" placeholder="Number" /></div></div><div class="p-form__field"><div class="p-form__label"><label for="read-only-(...)">Read Only</label></div><div class="p-form__input"><span id="read-only-\3"></span></div></div><div class="p-form__field"><div class="p-form__submit"><input type="submit" value="Submit" /></div></div></form>~',
      $form->render()
    );

    $form->number = 4;
    $form->text = 'abc';
    $this->assertRegExp(
      '~<form class="p-form" id="vbn" action="/test" method="post"><div class="p-form__field"><div class="p-form__label"><label for="text-(...)">Text</label></div><div class="p-form__input"><input type="text" id="text-\1" name="text" placeholder="Text" value="abc" /></div></div><div class="p-form__field"><div class="p-form__label"><label for="number-(...)">Number</label></div><div class="p-form__input"><input type="number" id="number-\2" name="number" placeholder="Number" value="4" /></div></div><div class="p-form__field"><div class="p-form__label"><label for="read-only-(...)">Read Only</label></div><div class="p-form__input"><span id="read-only-\3"></span></div></div><div class="p-form__field"><div class="p-form__submit"><input type="submit" value="Submit" /></div></div></form>~',
      $form->render()
    );

    $form->text->getDecorator()->getInput()->setId('myInput');
    $form->getDecorator()->setId('abc')->setAttribute('data-test', true);
    $this->assertRegExp(
      '~<form class="p-form" id="abc" action="/test" method="post" data-test><div class="p-form__field"><div class="p-form__label"><label for="myInput">Text</label></div><div class="p-form__input"><input type="text" id="myInput" name="text" placeholder="Text" value="abc" /></div></div><div class="p-form__field"><div class="p-form__label"><label for="number-(...)">Number</label></div><div class="p-form__input"><input type="number" id="number-\1" name="number" placeholder="Number" value="4" /></div></div><div class="p-form__field"><div class="p-form__label"><label for="read-only-(...)">Read Only</label></div><div class="p-form__input"><span id="read-only-\2"></span></div></div><div class="p-form__field"><div class="p-form__submit"><input type="submit" value="Submit" /></div></div></form>~',
      $form->render()
    );
    $this->assertTrue($form->getDecorator()->hasAttribute('data-test'));

    $form->getDecorator()->setId('abc')->removeAttribute('data-test');
    $this->assertRegExp(
      '~<form class="p-form" id="abc" action="/test" method="post"><div class="p-form__field"><div class="p-form__label"><label for="myInput">Text</label></div><div class="p-form__input"><input type="text" id="myInput" name="text" placeholder="Text" value="abc" /></div></div><div class="p-form__field"><div class="p-form__label"><label for="number-(...)">Number</label></div><div class="p-form__input"><input type="number" id="number-\1" name="number" placeholder="Number" value="4" /></div></div><div class="p-form__field"><div class="p-form__label"><label for="read-only-(...)">Read Only</label></div><div class="p-form__input"><span id="read-only-\2"></span></div></div><div class="p-form__field"><div class="p-form__submit"><input type="submit" value="Submit" /></div></div></form>~',
      $form->render()
    );
    $this->assertfalse($form->getDecorator()->hasAttribute('data-test'));

    // assert should not throw
    $form->assert();

    $form->readOnly->getDecorator()->getInput()->setId('ro');
    $form->number = 'abc';
    $decorator = $form->number->getDecorator();
    $decorator->getInput()->setId('myNum');
    $form->validate();
    $this->assertEquals(
      '<form class="p-form" id="abc" action="/test" method="post"><div class="p-form__field"><div class="p-form__label"><label for="myInput">Text</label></div><div class="p-form__input"><input type="text" id="myInput" name="text" placeholder="Text" value="abc" /></div></div><div class="p-form__field p-form__field--error"><div class="p-form__label"><label for="myNum">Number</label></div><div class="p-form__errors"><ul><li>must be a number</li></ul></div><div class="p-form__input"><input type="number" id="myNum" name="number" placeholder="Number" value="abc" /></div></div><div class="p-form__field"><div class="p-form__label"><label for="ro">Read Only</label></div><div class="p-form__input"><span id="ro"></span></div></div><div class="p-form__field"><div class="p-form__submit"><input type="submit" value="Submit" /></div></div></form>',
      $form->render()
    );
  }

  public function testAction()
  {
    $form = new EmptyForm();
    $this->assertEquals('<form class="p-form" method="post"></form>', $form->render());

    $form->setAction('/test-url');
    $this->assertEquals('<form class="p-form" method="post" action="/test-url"></form>', $form->render());

    $form->setMethod('get');
    $this->assertEquals('<form class="p-form" method="get" action="/test-url"></form>', $form->render());
  }
}
