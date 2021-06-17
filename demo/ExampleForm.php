<?php
namespace Demo\Form;

use Packaged\Form\Csrf\CsrfForm;
use Packaged\Form\DataHandlers\EnumDataHandler;
use Packaged\Form\DataHandlers\TextDataHandler;

class ExampleForm extends CsrfForm
{

  /** @var TextDataHandler */
  public $name;
  /** @var EnumDataHandler */
  public $carModel;

  protected function _initDataHandlers()
  {
    parent::_initDataHandlers();

    $this->name = TextDataHandler::i();
    $this->carModel = EnumDataHandler::i();
  }

  protected function _configureDataHandlers()
  {
    $this->carModel->getInput()->setAttribute('data-vehicle', 'car');
  }

}
