<?php
namespace PackagedUi\Tests\Form\Csrf;

use PackagedUi\Form\Csrf\CsrfForm;
use PHPUnit\Framework\TestCase;

class CsrfFormTest extends TestCase
{
  public function testCsrfToken()
  {
    $secret = 'user-secret';
    $form = new CsrfForm($secret);
    $formValue = $form->csrfToken->getValue();

    $this->assertTrue($form->csrfToken->isValid());
    $this->assertTrue($form->csrfToken->isValidValue($formValue));
    $form->csrfToken = $formValue;
    $this->assertTrue($form->csrfToken->isValid());

    $form = new CsrfForm($secret);
    $form->csrfToken->setValue($formValue);
    $this->assertTrue($form->csrfToken->isValid());

    $form = new CsrfForm($secret);
    $form->csrfToken->setValue('invalidToken');
    $this->assertNotTrue($form->csrfToken->isValid());
  }

  public function testRender()
  {
    $secret = 'user-secret';
    $form = new CsrfForm($secret);
    $html = $form->produceSafeHTML()->getContent();
    $this->assertRegExp(
      '/\<form method="POST"\>\<input type="hidden" value=".*" name="csrfToken" \/\>\<\/form\>/',
      $html
    );
  }
}