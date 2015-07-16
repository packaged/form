<?php
namespace Packaged\Form\Tests\Support;

class User
{
  public $username = 'fwefw';
  public $password;
  public $emptyValue;
  /**
   * @nullify
   */
  public $nullValue;

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
