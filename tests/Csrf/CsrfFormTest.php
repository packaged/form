<?php
namespace Packaged\Tests\Form\Csrf;

use Packaged\Form\Csrf\CsrfForm;
use PHPUnit\Framework\TestCase;

class CsrfFormTest extends TestCase
{
  public function testCsrfToken()
  {
    $secret = 'user-secret';
    $form = new CsrfForm($secret);
    $form->csrfToken->applyNewToken();
    $formValue = $form->csrfToken->getValue();

    self::assertTrue($form->csrfToken->isValid());
    self::assertTrue($form->csrfToken->isValidValue($formValue));

    $form->csrfToken->applyNewToken();
    $formValue = $form->csrfToken->getValue();
    self::assertTrue($form->csrfToken->isValid());
    self::assertTrue($form->csrfToken->isValidValue($formValue));
    $form->csrfToken = $formValue;
    self::assertTrue($form->csrfToken->isValid());

    $form = new CsrfForm($secret);
    $form->csrfToken->setValue($formValue);
    self::assertTrue($form->csrfToken->isValid());

    $form = new CsrfForm($secret);
    $form->csrfToken->setValue('invalidToken');
    self::assertFalse($form->csrfToken->isValid());
  }

  public function testRender()
  {
    $secret = 'user-secret';
    $form = new CsrfForm($secret);
    $html = $form->render();
    self::assertMatchesRegularExpression(
      '/<form class="p-form" method="post">  <input type="hidden" name="csrfToken" id=".+" value=".*" \/><div class="p-form__field"><div class="p-form__submit"><input type="submit" value="Submit" \/><\/div><\/div><\/form>/',
      $html
    );
  }
}
