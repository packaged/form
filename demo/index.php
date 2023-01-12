<?php

use Packaged\Form\DataHandlers\BooleanDataHandler;
use Packaged\Form\DataHandlers\EmailDataHandler;
use Packaged\Form\DataHandlers\EnumDataHandler;
use Packaged\Form\DataHandlers\FileDataHandler;
use Packaged\Form\DataHandlers\HiddenDataHandler;
use Packaged\Form\DataHandlers\IntegerDataHandler;
use Packaged\Form\DataHandlers\MultiLineTextDataHandler;
use Packaged\Form\DataHandlers\MultiValueEnumDataHandler;
use Packaged\Form\DataHandlers\ReadOnlyDataHandler;
use Packaged\Form\DataHandlers\SecureTextDataHandler;
use Packaged\Form\DataHandlers\TextDataHandler;
use Packaged\Form\Form\Form;
use Packaged\Helpers\Arrays;
use Packaged\SafeHtml\SafeHtml;
use Packaged\Validate\Validators\ConfirmationValidator;
use Packaged\Validate\Validators\EqualValidator;
use Packaged\Validate\Validators\FileSizeValidator;
use Packaged\Validate\Validators\RegexValidator;
use Packaged\Validate\Validators\RequiredValidator;
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
   * @var SecureTextDataHandler
   */
  public $confirmPassword;
  /**
   * @var HiddenDataHandler
   */
  public $secret;
  /**
   * @var EnumDataHandler
   */
  public $greedySelect;
  /**
   * @var ReadOnlyDataHandler
   */
  public $youCantTouchThis;

  /** @var IntegerDataHandler */
  public $age;

  /** @var FileDataHandler */
  public $profilePicture;
  /**
   * @var BooleanDataHandler
   */
  public $agree;

  /** @var \Packaged\Form\DataHandlers\MultiLineTextDataHandler */
  public $comments;

  protected function _initDataHandlers()
  {
    $this->name = TextDataHandler::i()->addValidator(new StringValidator(2, 20));
    $this->email = EmailDataHandler::i()
      ->setAutocomplete('email');
    $this->selection = EnumDataHandler::i(Arrays::fuse(['test1', 'test2', 'test3']))
      ->setDefaultValue($this->selection)
      ->styleSplit()
      ->addValidator(new EqualValidator('test3'));

    $this->greedySelect = MultiValueEnumDataHandler::i(Arrays::fuse(['apple', 'orange', 'pear']))->styleSplit();
    $this->password = SecureTextDataHandler::i()->setGuidance("(Min. 8 characters, 1 number, case-sensitive)")
      ->setAutocomplete('new-password')
      ->addValidator(new StringValidator(8))
      ->addValidator(new RegexValidator('/\d+/', 'must contain one number'));
    $this->confirmPassword = SecureTextDataHandler::i()
      ->setAutocomplete('new-password');
    $this->secret = HiddenDataHandler::i()->setValue('Form displayed at ' . date("Y-m-d H:i:s"));
    $this->agree = BooleanDataHandler::i()
      ->setGuidance(new SafeHtml('<a href="#">Terms & Conditions</a>'))
      ->setPlaceholder("Do you agree to our Terms?")
      ->addValidator(new RequiredValidator());
    $this->youCantTouchThis = ReadOnlyDataHandler::i()->setValue('Dare You');
    $this->age = IntegerDataHandler::i()->setLabel('How old are you?');
    $this->profilePicture = FileDataHandler::i()
      ->addValidator(new FileSizeValidator(1))
      ->setLabel("Profile Picture")
      ->setGuidance('Max Size 1mb');
    $this->comments = MultiLineTextDataHandler::i()
      ->setLabel("Any Comments?")
      ->setGuidance("Required")
      ->addValidator(new RequiredValidator());
    //$this->setHandlerDecorator(new InputOnlyDataHandlerDecorator(), 'agree');
  }

  protected function _configureDataHandlers()
  {
    parent::_configureDataHandlers();
    $this->confirmPassword->addValidator(new ConfirmationValidator($this->password->getName()));
  }
}

$data = array_merge($_POST, $_FILES);
$form = new DemoForm();
if(!empty($data))
{
  $errors = $form->hydrate($data);
}

?>
<html lang="en">
<head>
  <title>Form Demo</title>
  <link type="text/css" rel="stylesheet" href="../js/dist/form.min.css"/>
  <script src="../js/dist/form.min.js"></script>
  <style>
    body {
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      font-size: 13px;
    }

    table {
      margin: 0 20px 0 0;
      padding: 0;
      border-collapse: collapse;
    }

    th {
      text-align: right;
    }

    th, td {
      padding: 8px;
      border: 1px solid #ccc;
      margin: 0;
    }
  </style>
</head>
<body>

<h2>Example Form</h2>
<?php echo $form->render() ?>

<?php if(!empty($errors)): ?>
  <div>
    <h2>ERROR Data</h2>
    <table>
      <?php foreach($errors as $k => $v): ?>
        <tr>
          <th><?= $k; ?></th>
          <td>
            <?php foreach($v as $o): ?>
              <?= $o->getMessage() ?>
            <?php endforeach; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
<?php endif; ?>

<?php if(!empty($data)): ?>
  <div style="display: flex">
    <div><h2>POST Data</h2>
      <table>
        <?php foreach($data as $k => $v): ?>
          <tr>
            <th><?= $k; ?></th>
            <td><?php var_dump($v); ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
    <div><h2>Form Data</h2>
      <?= $form->selection->getValue() ?>
      <table>
        <?php foreach($form->getFormData() as $k => $v): ?>
          <tr>
            <th><?= $k; ?></th>
            <td><?php var_dump($v); ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if($form->profilePicture->hasUpload()): ?>
          <tr>
            <th>File:</th>
            <td>
              <h4><?= $form->profilePicture->getFileName(); ?></h4>
              <?php $content = file_get_contents($form->profilePicture->getFileLocation()); ?>
              <?php if(substr($form->profilePicture->getFileType(), 0, 5) == 'image'): ?>
                <img src="data:<?= $form->profilePicture->getFileType() ?>;base64,<?= base64_encode($content); ?>">
              <?php else: ?>
                <pre><?= $content; ?></pre>
              <?php endif; ?>
            </td>
          </tr>
        <?php endif; ?>
      </table>
    </div>
  </div>
  <br/>
<?php endif; ?>

<script>
  document.addEventListener('form-handler-validation', function (e)
  {
    console.log('handler', e.detail);
  });
  document.addEventListener('form-validation', function (e)
  {
    console.log('form', e.detail);
  });
  document.addEventListener('submit', function (e)
  {
    console.log('submit', e.detail);
  });
</script>
</body>
</html>
