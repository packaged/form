<?php
namespace Packaged\Form;

use Packaged\Form\Render\FormElementRenderer;
use Packaged\Form\Render\IFormElementRenderer;
use Packaged\Helpers\Strings;

class FormElement
{
  use OptionsTrait;

  const TEXT = 'text';
  const HIDDEN = 'hidden';
  const PASSWORD = 'password';
  const RADIO = 'radio';
  const CHECKBOX = 'checkbox';
  const MULTI_CHECKBOX = 'multi.checkbox';
  const SELECT = 'select';
  const TEXTAREA = 'textarea';
  const FILE = 'file';
  const IMAGE = 'image';
  const BUTTON = 'button';
  const RESET = 'reset';
  const SUBMIT = 'submit';

  /**
   * @const NONE instructs the renderer to not render this field
   */
  const NONE = 'none';

  const COLOUR = 'color';
  const DATE = 'date';
  const DATETIME = 'datetime';
  const DATETIME_LOCAL = 'datetime-local';
  const EMAIL = 'email';
  const MONTH = 'month';
  const NUMBER = 'number';
  const RANGE = 'range';
  const SEARCH = 'search';
  const TEL = 'tel';
  const TIME = 'time';
  const URL = 'url';
  const WEEK = 'week';

  /**
   * Label Positions
   */

  const LABEL_AFTER = 'after';
  const LABEL_BEFORE = 'before';
  const LABEL_NONE = 'none';
  const LABEL_SURROUND = 'surround.left';
  const LABEL_SURROUND_LEFT = 'surround.left';
  const LABEL_SURROUND_RIGHT = 'surround.right';

  /**
   * @var Form
   */
  protected $_form;
  /**
   * @var IFormElementRenderer
   */
  protected $_renderer;

  protected $_type = self::TEXT;
  protected $_label;
  protected $_labelPosition;
  protected $_name;
  protected $_id;

  public function __construct(
    Form $form, $name, $type = self::TEXT, $label = null,
    $labelPosition = self::LABEL_BEFORE
  )
  {
    $this->_name          = $name;
    $this->_id            = $form->getId() . '-' . Strings::urlize($name);
    $this->_type          = $type;
    $this->_label         = $label === null ? Strings::humanize($name) : $label;
    $this->_labelPosition = $labelPosition;
    $this->_form          = $form;
  }

  public function setType($type)
  {
    $this->_type = $type;
    return $this;
  }

  public static function calculateType($name)
  {
    switch(Strings::urlize($name))
    {
      case 'enabled':
      case 'disabled':
      case 'active':
      case 'suspended':
        return self::CHECKBOX;
      case 'search':
      case 'query':
        return self::SEARCH;
      case 'age':
        return self::NUMBER;
      case 'password':
        return self::PASSWORD;
      case 'email':
      case 'email-address':
      case 'emailaddress':
        return self::EMAIL;
      case 'tel':
      case 'telephone':
      case 'mobile':
      case 'phone':
      case 'cell':
        return self::TEL;
      case 'description':
      case 'about':
      case 'information':
      case 'info':
        return self::TEXTAREA;
      case 'time':
        return self::TIME;
      case 'url':
      case 'uri':
      case 'website':
      case 'site':
        return self::URL;
      case 'colour':
      case 'color':
        return self::COLOUR;
      case 'date':
      case 'start-date':
      case 'end-date':
      case 'dob':
      case 'date-of-birth':
      case 'birthday':
        return self::DATE;
      case 'datetime':
        return self::DATETIME;
      case 'file':
      case 'upload':
        return self::FILE;
      default:
        return self::TEXT;
    }
  }

  public function getValue()
  {
    return $this->_form->getValue($this->_name);
  }

  public function getName()
  {
    return $this->_name;
  }

  public function getId()
  {
    return $this->_id;
  }

  public function getType()
  {
    return $this->_type;
  }

  public function getLabel()
  {
    return $this->_label;
  }

  public function getLabelPosition()
  {
    return $this->_labelPosition;
  }

  public function setRenderer(IFormElementRenderer $renderer)
  {
    $this->_renderer = $renderer;
    return $this;
  }

  public function render()
  {
    if($this->_renderer === null)
    {
      $this->_renderer = new FormElementRenderer();
    }

    return $this->_renderer->render($this);
  }

  public function __toString()
  {
    return $this->render();
  }
}
