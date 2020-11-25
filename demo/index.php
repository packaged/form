<?php

use Packaged\Form\DataHandlers\BooleanDataHandler;
use Packaged\Form\DataHandlers\EmailDataHandler;
use Packaged\Form\DataHandlers\EnumDataHandler;
use Packaged\Form\DataHandlers\HiddenDataHandler;
use Packaged\Form\DataHandlers\IntegerDataHandler;
use Packaged\Form\DataHandlers\MultiValueEnumDataHandler;
use Packaged\Form\DataHandlers\ReadOnlyDataHandler;
use Packaged\Form\DataHandlers\SecureTextDataHandler;
use Packaged\Form\DataHandlers\TextDataHandler;
use Packaged\Form\Form\Form;
use Packaged\Helpers\Arrays;
use Packaged\Validate\Validators\EqualValidator;
use Packaged\Validate\Validators\StringValidator;

require('../vendor/autoload.php');

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
  public $age;

  protected function _initDataHandlers()
  {
    $this->name = TextDataHandler::i()->addValidator(new StringValidator(2, 20));
    $this->email = new EmailDataHandler();
    $this->selection = (new EnumDataHandler(Arrays::fuse(['test1', 'test2', 'test3'])))
      ->setDefaultValue($this->selection)->addValidator(new EqualValidator('test3'));
    $this->selection->styleSplit();
    $this->greedySelect = new MultiValueEnumDataHandler(Arrays::fuse(['apple', 'orange', 'pear']));
    $this->greedySelect->styleSplit();
    $this->password = new SecureTextDataHandler();
    $this->secret = HiddenDataHandler::i()->setValue('Form displayed at ' . date("Y-m-d H:i:s"));
    $this->agree = BooleanDataHandler::i()->setPlaceholder('Do you agree?');
    $this->youCantTouchThis = ReadOnlyDataHandler::i()->setValue('Dare You');
    $this->age = IntegerDataHandler::i()->setLabel('How old are you?');
  }
}

$data = $_POST;
$form = new DemoForm();
if(!empty($data))
{
  $form->hydrate($data);
  $form->validate();
}
?>
<html>
<head>
  <link type="text/css" rel="stylesheet" href="demo.css"/>
  <script type="module" src="assets/demo.min.js"></script>
</head>
<body>
<?php if(!empty($data)): ?>
  <div style="display: flex">
    <div><h2>POST Data</h2>
      <pre><?php print_r($data); ?></pre>
    </div>
    <div><h2>Form Data</h2>
      <pre><?php print_r($form->getFormData()); ?></pre>
    </div>
  </div>
  <br/>
<?php endif; ?>
<h2>Example Form</h2>
<?php echo $form->render() ?>
</body>
</html>
