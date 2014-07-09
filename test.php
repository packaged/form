<?php
include('vendor/autoload.php');

class MyForm extends \Packaged\Form\Form
{
  public $name = 'Davide';
  public $email;
  public $age;
  public $dob;
  public $about = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.';
  public $colour;
  /**
   * @inputType  select
   * @values     UK,GB,CA,US,KDH
   */
  public $country = 'GB';
}

class Rubbish
{
  public $about = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.';
  public $colour;
  /**
   * @inputType  select
   * @values     UK,GB,CA,US,KDH
   */
  public $country = 'GB';
}

$form = \Packaged\Form\Form::fromClass(new Rubbish());
echo $form;
echo '<hr/>';

$form = new MyForm();
$form->getElement('country')->setOption('values', ['FW', 'Tom', 'Davide']);
echo $form;
