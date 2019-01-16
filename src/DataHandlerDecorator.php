<?php
namespace PackagedUi\Form;

use Packaged\Glimpse\Core\HtmlTag;

interface DataHandlerDecorator
{
  public function buildElement(FormDataHandler $handler, array $options = null): HtmlTag;
}
