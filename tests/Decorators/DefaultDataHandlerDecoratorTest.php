<?php

namespace Packaged\Tests\Form\Decorators;

use Packaged\Form\DataHandlers\TextDataHandler;
use Packaged\Form\Decorators\DefaultDataHandlerDecorator;
use PHPUnit\Framework\TestCase;

class DefaultDataHandlerDecoratorTest extends TestCase
{
  private function _getTextDataHandler()
  {
    $dh = TextDataHandler::i();
    $dh->setName('Hello World');
    return $dh;
  }

  public function testDefaultDataHandlerDecoratorLabel()
  {
    $dh = $this->_getTextDataHandler();
    $dec = new DefaultDataHandlerDecorator();
    $dec->setHandler($dh);

    self::assertStringContainsString(
      'label for="hello-world',
      $dec->render()
    );

    self::assertStringContainsString(
      'Hello World',
      $dec->render()
    );

    self::assertStringContainsString(
      'p-form__input',
      $dec->render()
    );

    $dh->setLabel('Example Label');
    self::assertStringContainsString(
      'label for="hello-world',
      $dec->render()
    );

    self::assertStringContainsString(
      'Example Label',
      $dec->render()
    );

    $dh->setLabel(false);
    self::assertStringNotContainsString(
      'label for="hello-world',
      $dec->render()
    );

    self::assertStringNotContainsString(
      'p-form__label',
      $dec->render()
    );
  }
}
