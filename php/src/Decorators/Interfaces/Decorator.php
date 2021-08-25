<?php
namespace Packaged\Form\Decorators\Interfaces;

use Packaged\SafeHtml\ISafeHtmlProducer;
use Packaged\Ui\Renderable;

/**
 * Decorators are responsible for creating elements
 *
 * @package Packaged\Form
 */
interface Decorator extends Renderable, ISafeHtmlProducer
{
  public function __toString();
}
