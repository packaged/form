<?php
require('../vendor/autoload.php');

use Demo\Form\ExampleForm;

$data = array_merge($_POST, $_FILES);
$form = new ExampleForm('secrets');

$form->carModel->setOptions(
  [
    'Ford',
    'Mercedes',
    'Audi',
    'Jaguar',
    'Pontiac',
  ]
);

if(!empty($data))
{
  $form->hydrate($data);
  $form->validate();
}

?>
<html>
<head>
  <link type="text/css" rel="stylesheet" href="../resources/form.min.css"/>
  <script type="module" src="../resources/form.min.js"></script>
  <style>
    body {
      font-family: "Helvetica Neue", Helvetica, Arial;
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
                <textarea><?= $content; ?></textarea>
              <?php endif; ?>
            </td>
          </tr>
        <?php endif; ?>
      </table>
    </div>
  </div>
  <br/>
<?php endif; ?>
</body>
</html>
