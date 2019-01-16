<?php
namespace PackagedUi\Form;

use Packaged\Glimpse\Core\HtmlTag;

interface DataHandlerDecorator
{
  public function buildElement(DataHandler $handler, array $options = null): HtmlTag;
}
