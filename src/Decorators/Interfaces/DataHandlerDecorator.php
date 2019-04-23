<?php
namespace Packaged\Form\Decorators\Interfaces;

use Packaged\Form\DataHandlers\Interfaces\DataHandler;
use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Ui\Html\HtmlElement;

/**
 * DataHandlerDecorator are responsible for creating display elements from DataHandler
 *
 * @package Packaged\Form
 */
interface DataHandlerDecorator extends Decorator
{
  /**
   * @param DataHandler $handler
   *
   * @return $this
   */
  public function setHandler(DataHandler $handler);

  public function getInput(): HtmlElement;

  public function getLabel(): ?HtmlTag;
}
