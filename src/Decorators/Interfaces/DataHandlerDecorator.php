<?php
namespace PackagedUi\Form\Decorators\Interfaces;

use PackagedUi\Form\DataHandlers\Interfaces\DataHandler;

/**
 * DataHandlerDecorator are responsible for creating display elements from DataHandler
 *
 * @package PackagedUi\Form
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
