<?php
namespace Packaged\Tests\Form\DataHandlers;

use Packaged\Form\DataHandlers\ReadOnlyDataHandler;
use Packaged\Form\DataHandlers\TextDataHandler;
use Packaged\Tests\Form\Supporting\DataHandlers\TestIntegerDataHandler;
use PHPUnit\Framework\TestCase;

class AbstractDataHandlerTest extends TestCase
{
  public function testAbstract()
  {
    $fdh = new TextDataHandler();
    self::assertEmpty($fdh->getValue());
    $fdh->setValue('abc');
    self::assertEquals('abc', $fdh->getValue());
    self::assertTrue($fdh->isValid());
    self::assertTrue($fdh->isValidValue(''));
  }

  public function testValidation()
  {
    $fdh = new TestIntegerDataHandler();
    self::assertTrue($fdh->isValid());
    $fdh->setValue('1abc');
    self::assertFalse($fdh->isValid());
    $fdh->setValue(1);
    self::assertTrue($fdh->isValid());
  }

  public function testAccessor()
  {
    $fdh = new TextDataHandler();

    self::assertEmpty($fdh->getLabel());
    $fdh->setLabel('abc');
    self::assertEquals('abc', $fdh->getLabel());

    self::assertEmpty($fdh->getPlaceholder());
    $fdh->setPlaceholder('abc');
    self::assertEquals('abc', $fdh->getPlaceholder());

    self::assertEmpty($fdh->getDefaultValue());
    $fdh->setDefaultValue('abc');
    self::assertEquals('abc', $fdh->getDefaultValue());
  }

  public function testRender()
  {
    $fdh = new ReadOnlyDataHandler();
    self::assertEquals(
      '<input type="text" readonly />',
      $fdh->getInput()->render()
    );

    $fdh = new ReadOnlyDataHandler();
    $fdh->setName('myName');
    self::assertMatchesRegularExpression(
      '~<input type="text" readonly name="myName" id="my-name-.+" />~',
      $fdh->getInput()->render()
    );

    $fdh = new ReadOnlyDataHandler();
    $fdh->setName('myName');
    $fdh->setId('my-name');
    $fdh->setLabel('This is my input');
    self::assertEquals(
      '<input type="text" readonly name="myName" id="my-name" placeholder="My Name" />',
      $fdh->getInput()->render()
    );

    $fdh = new ReadOnlyDataHandler();
    $fdh->setName('myName');
    $fdh->setId('my-name');
    $fdh->setLabel('This is my input');
    $fdh->setValue('my value');
    self::assertEquals(
      '<input type="text" readonly name="myName" id="my-name" value="my value" placeholder="My Name" />',
      $fdh->getInput()->render()
    );
  }
}
