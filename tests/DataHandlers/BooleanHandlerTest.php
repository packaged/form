<?php

namespace Packaged\Tests\Form\DataHandlers;

use Packaged\Form\DataHandlers\BooleanDataHandler;
use PHPUnit\Framework\TestCase;

class BooleanHandlerTest extends TestCase
{
  public function testCheckbox()
  {
    $h = new BooleanDataHandler();
    self::assertFalse($h->getValue());

    $h->setValue('sgfsafhasdg');
    self::assertFalse($h->getValue());

    $h->setValue('no');
    self::assertFalse($h->getValue());

    $h->setName('mychoice');
    self::assertMatchesRegularExpression(
      '~<input type="checkbox" name="mychoice" id="mychoice-.+" value="true" />~',
      $h->getInput()->render()
    );

    $h = new BooleanDataHandler();
    $h->setValue('yes');
    self::assertTrue($h->getValue());

    self::assertEquals(
      '<input type="checkbox" value="true" checked />',
      $h->getInput()->render()
    );
  }
}
