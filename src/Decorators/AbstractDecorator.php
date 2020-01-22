<?php
namespace Packaged\Form\Decorators;

use Packaged\Form\Decorators\Interfaces\Decorator;
use Packaged\Ui\Html\TemplatedHtmlElement;

abstract class AbstractDecorator extends TemplatedHtmlElement implements Decorator
{
  public static function i()
  {
    if(func_num_args() > 0)
    {
      return new static(...func_get_args());
    }
    return new static();
  }
}
