<?php

namespace Packaged\Tests\Form;

use Packaged\Form\DataHandlers\TextDataHandler;
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
      '/<form id="vbn" method="post" class="p-form" action="\/test"><div class="p-form-field"><div class="p-form--label"><label for="text-(...)">Text<\/label><\/div><div class="p-form--input"><input type="text" name="text" placeholder="Text" id="text-\1" \/><\/div><\/div><div class="p-form-field"><div class="p-form--label"><label for="number-(...)">Number<\/label><\/div><div class="p-form--input"><input type="number" name="number" placeholder="Number" id="number-\2" \/><\/div><\/div><div class="p-form-field"><div class="p-form--label"><label for="read-only-(...)">Read Only<\/label><\/div><div class="p-form--input"><span id="read-only-\3"><\/span><\/div><\/div><div class="p-form-field"><div class="p-form--submit"><input type="submit" value="Submit" \/><\/div><\/div><\/form>/',
      $form->render()
    );

    $form->number = 4;
    $form->text = 'abc';
    $this->assertRegExp(
      '/<form id="vbn" method="post" class="p-form" action="\/test"><div class="p-form-field"><div class="p-form--label"><label for="text-(...)">Text<\/label><\/div><div class="p-form--input"><input type="text" name="text" placeholder="Text" value="abc" id="text-\1" \/><\/div><\/div><div class="p-form-field"><div class="p-form--label"><label for="number-(...)">Number<\/label><\/div><div class="p-form--input"><input type="number" name="number" placeholder="Number" value="4" id="number-\2" \/><\/div><\/div><div class="p-form-field"><div class="p-form--label"><label for="read-only-(...)">Read Only<\/label><\/div><div class="p-form--input"><span id="read-only-\3"><\/span><\/div><\/div><div class="p-form-field"><div class="p-form--submit"><input type="submit" value="Submit" \/><\/div><\/div><\/form>/',
      $form->render()
    );

    $form->text->getDecorator()->setId('myInput');
    $form->getDecorator()->setId('abc')->addAttribute('data-test', true);
    $this->assertRegExp(
      '/<form id="abc" data-test method="post" class="p-form" action="\/test"><div class="p-form-field"><div class="p-form--label"><label for="myInput">Text<\/label><\/div><div class="p-form--input"><input type="text" id="myInput" name="text" placeholder="Text" value="abc" \/><\/div><\/div><div class="p-form-field"><div class="p-form--label"><label for="number-(...)">Number<\/label><\/div><div class="p-form--input"><input type="number" name="number" placeholder="Number" value="4" id="number-\1" \/><\/div><\/div><div class="p-form-field"><div class="p-form--label"><label for="read-only-(...)">Read Only<\/label><\/div><div class="p-form--input"><span id="read-only-\2"><\/span><\/div><\/div><div class="p-form-field"><div class="p-form--submit"><input type="submit" value="Submit" \/><\/div><\/div><\/form>/',
      $form->render()
    );
    $this->assertTrue($form->getDecorator()->hasAttribute('data-test'));

    $form->getDecorator()->setId('abc')->removeAttribute('data-test');
    $this->assertRegExp(
      '/<form id="abc" method="post" class="p-form" action="\/test"><div class="p-form-field"><div class="p-form--label"><label for="myInput">Text<\/label><\/div><div class="p-form--input"><input type="text" id="myInput" name="text" placeholder="Text" value="abc" \/><\/div><\/div><div class="p-form-field"><div class="p-form--label"><label for="number-(...)">Number<\/label><\/div><div class="p-form--input"><input type="number" name="number" placeholder="Number" value="4" id="number-\1" \/><\/div><\/div><div class="p-form-field"><div class="p-form--label"><label for="read-only-(...)">Read Only<\/label><\/div><div class="p-form--input"><span id="read-only-\2"><\/span><\/div><\/div><div class="p-form-field"><div class="p-form--submit"><input type="submit" value="Submit" \/><\/div><\/div><\/form>/',
      $form->render()
    );
    $this->assertfalse($form->getDecorator()->hasAttribute('data-test'));

    // assert should not throw
    $form->assert();

    $form->readOnly->getDecorator()->setId('ro');
    $form->number = 'abc';
    $decorator = $form->number->getDecorator();
    $decorator->setId('myNum');
    $form->validate();
    $this->assertEquals(
      '<form id="abc" method="post" class="p-form" action="/test"><div class="p-form-field"><div class="p-form--label"><label for="myInput">Text</label></div><div class="p-form--input"><input type="text" id="myInput" name="text" placeholder="Text" value="abc" /></div></div><div class="p-form-field"><div class="p-form--label"><label for="myNum">Number</label></div><div class="p-form--errors"><ul><li>must be a number</li></ul></div><div class="p-form--input"><input type="number" id="myNum" name="number" placeholder="Number" value="abc" /></div></div><div class="p-form-field"><div class="p-form--label"><label for="ro">Read Only</label></div><div class="p-form--input"><span id="ro"></span></div></div><div class="p-form-field"><div class="p-form--submit"><input type="submit" value="Submit" /></div></div></form>',
      $form->render()
    );

    $decorator->setFormatCallback(function ($input, $label, $err) { return $input; });
    $this->assertEquals(
      '<input type="number" id="myNum" name="number" placeholder="Number" value="abc" />',
      $decorator->render()
    );
  }
}
