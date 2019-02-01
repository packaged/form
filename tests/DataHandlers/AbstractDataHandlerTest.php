<?php
namespace PackagedUi\Tests\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use PackagedUi\Form\Decorators\InputDecorator;
use PackagedUi\Tests\Form\Supporting\DataHandlers\TestAbstractDataHandler;
use PackagedUi\Tests\Form\Supporting\DataHandlers\TestIntegerDataHandler;
use PHPUnit\Framework\TestCase;

class AbstractDataHandlerTest extends TestCase
{
  public function testAbstract()
  {
    $fdh = new TestAbstractDataHandler();
    $this->assertEmpty($fdh->getValue());
    $fdh->setValue('abc');
    $this->assertEquals('abc', $fdh->getValue());
    $this->assertTrue($fdh->isValid());
    $this->assertTrue($fdh->isValidValue(''));

    $this->assertNotNull($fdh->getDecorator());
  }

  public function testValidation()
  {
    $fdh = new TestIntegerDataHandler();
    $this->assertTrue($fdh->isValid());
    $fdh->setValue('1abc');
    $this->assertFalse($fdh->isValid());
    $fdh->setValue(1);
    $this->assertTrue($fdh->isValid());
  }

  public function testDecorator()
  {
    $fdh = new TestAbstractDataHandler();
    $defaultDecorator = $fdh->getDecorator();
    $newDecorator = new InputDecorator();
    $newDecorator->setType(Input::TYPE_DATE);
    $this->assertSame($defaultDecorator, $fdh->getDecorator());
    $fdh->setDecorator($newDecorator);
    $this->assertSame($newDecorator, $fdh->getDecorator());
  }

  public function testAccessor()
  {
    $fdh = new TestAbstractDataHandler();

    $this->assertEmpty($fdh->getLabel());
    $fdh->setLabel('abc');
    $this->assertEquals('abc', $fdh->getLabel());

    $this->assertEmpty($fdh->getPlaceholder());
    $fdh->setPlaceholder('abc');
    $this->assertEquals('abc', $fdh->getPlaceholder());

    $this->assertEmpty($fdh->getDefaultValue());
    $fdh->setDefaultValue('abc');
    $this->assertEquals('abc', $fdh->getDefaultValue());
  }

  public function testRender()
  {
    $fdh = new TestAbstractDataHandler();
    $this->assertEquals('<div class="form-group"><input type="text" /></div>', $fdh->getDecorator()->render());

    $fdh->setName('myName');
    $this->assertRegExp(
      '/\<div class="form-group"\>\<label for="(my-name-...)"\>My Name\<\/label\>\<input type="text" name="myName" id="\1" \/\>\<\/div\>/',
      $fdh->getDecorator()->render()
    );

    $fdh->setLabel('This is my input');
    $this->assertRegExp(
      '/\<div class="form-group"\>\<label for="(my-name-...)"\>This is my input\<\/label\>\<input type="text" name="myName" id="\1" \/\>\<\/div\>/',
      $fdh->getDecorator()->render()
    );
  }
}
