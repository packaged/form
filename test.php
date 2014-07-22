<?php
include('vendor/autoload.php');

class MyForm extends \Packaged\Form\Form
{
  public $name = 'Davide';
  /**
   * @placeholder Please enter your email address
   * @multiple
   * @name emailaddress
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

class User
{
  public $username = 'fwefw';
  public $password;

  /**
   * @var Website[]
   */
  public $urls;

  /**
   * @var Contact
   */
  public $billing;
  /**
   * @var Contact
   */
  public $delivery;
}

class Website
{
  public $name;
  public $url;
}

class Contact
{
  public $name = 'Davide';
  public $email;
  public $phone;
  /**
   * @inputType textarea
   */
  public $address;
}

$user = new User();

$form = \Packaged\Form\Form::fromClass($user);
$form->hydrate($_POST);
echo $form;

if(false)
{
  $form = \Packaged\Form\Form::fromClass(new Rubbish());
  echo $form;
  echo '<hr/>';

  $form = new MyForm();
  $form->hydrate($_POST);
  echo $form;
}
