<?php

use Packaged\Form\DataHandlers\EnumDataHandler;
use Packaged\Form\DataHandlers\TextDataHandler;
use Packaged\Form\Form\Form;
use Packaged\Validate\Validators\EmailValidator;

require('vendor/autoload.php');

class DemoForm extends Form
{
  /**
   * @var TextDataHandler
   */
  public $name;
  /**
   * @var TextDataHandler
   */
  public $email;
  /**
   * @var TextDataHandler
   */
  public $selection;

  protected function _initDataHandlers()
  {
    $this->name = new TextDataHandler();
    $this->email = new TextDataHandler();
    $this->email->addValidator(new EmailValidator());
    $this->selection = new EnumDataHandler();
    $this->selection->setOptions(['test1', 'test2', 'test3']);
  }
}

?>
<html>
<head>
  <link type="text/css" rel="stylesheet" href="./assets/form.css"/>
</head>
<body>
<div class="p-form__field">
  <div class="p-form__label">Not a real field</div>
  <div class="p-form__input">some placeholder text</div>
</div>
<?= (new DemoForm())->render() ?>


</body>
</html>
