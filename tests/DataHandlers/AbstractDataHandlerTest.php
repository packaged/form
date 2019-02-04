<?php
namespace PackagedUi\Tests\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use PackagedUi\Form\DataHandlers\ReadOnlyDataHandler;
use PackagedUi\Form\DataHandlers\TextDataHandler;
use PackagedUi\Form\Decorators\InputDecorator;
use PackagedUi\Tests\Form\Supporting\DataHandlers\TestIntegerDataHandler;
use PHPUnit\Framework\TestCase;

class AbstractDataHandlerTest extends TestCase
{
  public function testAbstract()
  {
    $fdh = new TextDataHandler();
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
    $fdh = new TextDataHandler();
    $defaultDecorator = $fdh->getDecorator();
    $newDecorator = new InputDecorator();
    $newDecorator->setType(Input::TYPE_DATE);
    $this->assertSame($defaultDecorator, $fdh->getDecorator());
    $fdh->setDecorator($newDecorator);
    $this->assertSame($newDecorator, $fdh->getDecorator());
  }

  public function testAccessor()
  {
    $fdh = new TextDataHandler();

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
    $fdh = new ReadOnlyDataHandler();
    $this->assertEquals('<div class="form-group"><span></span></div>', $fdh->getDecorator()->render());

    $fdh->setName('myName');
    $this->assertRegExp(
      '/\<div class="form-group"\>\<label for="(my-name-...)"\>My Name\<\/label\>\<span id="\1"\>\<\/span\>\<\/div\>/',
      $fdh->getDecorator()->render()
    );

    $fdh->setLabel('This is my input');
    $this->assertRegExp(
      '/\<div class="form-group"\>\<label for="(my-name-...)"\>This is my input\<\/label\>\<span id="\1"\>\<\/span\>\<\/div\>/',
      $fdh->getDecorator()->render()
    );
  }
}
