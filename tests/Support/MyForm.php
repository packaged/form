<?php
namespace Packaged\Form\Tests\Support;

use Packaged\Form\Form;

class MyForm extends Form
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
