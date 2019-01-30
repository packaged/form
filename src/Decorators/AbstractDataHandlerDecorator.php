<?php
namespace PackagedUi\Form\Decorators;

use PackagedUi\Form\DataHandlers\Interfaces\DataHandler;
use PackagedUi\Form\Decorators\Interfaces\DataHandlerDecorator;

abstract class AbstractDataHandlerDecorator extends AbstractDecorator implements DataHandlerDecorator
{
  /**
   * @var DataHandler
   */
  protected $_handler;
  protected $_options;

  public function setHandler(DataHandler $handler)
  {
    $this->_handler = $handler;
    return $this;
  }

  public function setOptions(array $options)
  {
    $this->_options = $options;
    return $this;
  }
}
