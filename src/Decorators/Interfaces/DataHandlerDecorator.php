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

  /**
   * @param array $options
   *
   * @return $this
   */
  public function setOptions(array $options);
}
