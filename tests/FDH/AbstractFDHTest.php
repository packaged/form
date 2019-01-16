<?php
namespace PackagedUi\Tests\Form\FDH;

use PackagedUi\Tests\Form\Supporting\FDH\TestAbstractFDH;
use PackagedUi\Tests\Form\Supporting\FDH\TestIntegerFDH;
use PHPUnit\Framework\TestCase;

class AbstractFDHTest extends TestCase
{
  public function testAbstract()
  {
    $fdh = new TestAbstractFDH();
    $this->assertEmpty($fdh->getValue());
    $fdh->setValue('abc');
    $this->assertEquals('abc', $fdh->getValue());
    $this->assertTrue($fdh->isValid());
    $this->assertTrue($fdh->isValidValue(''));

    $this->assertNotNull($fdh->getDefaultDecorator());
  }

  public function testValidation()
  {
    $fdh = new TestIntegerFDH();
    $this->assertTrue($fdh->isValid());
    $fdh->setValue('1abc');
    $this->assertFalse($fdh->isValid());
    $fdh->setValue(1);
    $this->assertTrue($fdh->isValid());
  }
}
