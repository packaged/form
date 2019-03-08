<?php
namespace Packaged\Form\Decorators\Interfaces;

use Packaged\Form\DataHandlers\Interfaces\DataHandler;

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

  public function setFormatCallback(callable $callable);
}
