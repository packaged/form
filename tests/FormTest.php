<?php
namespace Packaged\Form\Tests;

use Packaged\Form\Form;
use Packaged\Form\Tests\Support\MyForm;
use Packaged\Form\Tests\Support\User;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
  public function testFromClass()
  {
    $user = new User();
    $form = Form::fromClass($user);
    $form->hydrate(
      [
        'username'   => 'myuser',
        'password'   => 'test',
        'null_value' => '',
        'emptyValue' => '',
      ]
    );
    $this->assertEquals('myuser', $form->getValue('username'));
    $this->assertSame(null, $form->getValue('nullValue'));
    $this->assertSame('', $form->getValue('emptyValue'));
  }

  public function testForm()
  {
    $form = new MyForm();
    $form->hydrate(['name' => 'myname']);
    $this->assertEquals('myname', $form->name);

    $this->assertEquals('123', $form->getElement('dataTest')->getAttribute('data-test'));
  }
}
