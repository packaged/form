<?php

namespace PackagedUi\Tests\Form\FDH;

use Packaged\Glimpse\Tags\Form\Input;
use PackagedUi\Form\FDH\TextFDH;
use PHPUnit\Framework\TestCase;

class TextFDHTest extends TestCase
{
  public function testType()
  {
    $this->assertEquals(Input::TYPE_TEXT, (new TextFDH())->getType());
  }
}
