<?php
include('vendor/autoload.php');

class MyForm extends \Packaged\Form\Form
{
  public $name = 'Davide';
  /**
   * @placeholder Please enter your email address
   * @multiple
   */
  public $email;
  public $age;
  public $dob;
  /**
   * @rows 6
   * @cols 230
   */
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
$form->hydrate($_POST);
echo $form;
