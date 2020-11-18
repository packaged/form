<?php

use Packaged\Form\DataHandlers\BooleanDataHandler;
use Packaged\Form\DataHandlers\EmailDataHandler;
use Packaged\Form\DataHandlers\EnumDataHandler;
use Packaged\Form\DataHandlers\HiddenDataHandler;
use Packaged\Form\DataHandlers\MultiValueEnumDataHandler;
use Packaged\Form\DataHandlers\ReadOnlyDataHandler;
use Packaged\Form\DataHandlers\SecureTextDataHandler;
use Packaged\Form\DataHandlers\TextDataHandler;
use Packaged\Form\Form\Form;
use Packaged\Helpers\Arrays;

require('vendor/autoload.php');

class DemoForm extends Form
{
  /**
   * @var TextDataHandler
   */
  public $name;
  /**
   * @var EmailDataHandler
   */
  public $email;
  /**
   * @var EnumDataHandler
   */
  public $selection = 'test2';
  /**
   * @var SecureTextDataHandler
   */
  public $password;
  /**
   * @var HiddenDataHandler
   */
  public $secret;
  /**
   * @var BooleanDataHandler
   */
  public $agree;
  /**
   * @var EnumDataHandler
   */
  public $greedySelect;
  /**
   * @var ReadOnlyDataHandler
   */
  public $youCantTouchThis;

  protected function _initDataHandlers()
  {
    $this->name = new TextDataHandler();
    $this->email = new EmailDataHandler();
    $this->selection = (new EnumDataHandler(Arrays::fuse(['test1', 'test2', 'test3'])))
      ->setDefaultValue($this->selection);
    $this->greedySelect = new MultiValueEnumDataHandler(Arrays::fuse(['apple', 'orange', 'pear']));
    $this->password = new SecureTextDataHandler();
    $this->secret = new HiddenDataHandler();
    $this->agree = BooleanDataHandler::i()->setPlaceholder('Do you agree?');
    $this->youCantTouchThis = ReadOnlyDataHandler::i();
  }
}

?>
<html>
<head>
  <link type="text/css" rel="stylesheet" href="./assets/form.css"/>
</head>
<body>
<?= (new DemoForm())->render() ?>


</body>
</html>
