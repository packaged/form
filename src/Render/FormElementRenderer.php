<?php
namespace Packaged\Form\Render;

use Packaged\Form\FormElement;
use Packaged\Helpers\Arrays;
use Packaged\Helpers\Strings;

class FormElementRenderer implements IFormElementRenderer
{
  protected $_template;

  /**
   * @param null|string|callable $template
   */
  public function __construct($template = null)
  {
    $this->_template = $template;
  }

  public function setTemplate($template)
  {
    $this->_template = $template;
    return $this;
  }

  public function getTemplate()
  {
    return $this->_template;
  }

  public function defaultTemplate(FormElement $element)
  {
    switch($element->getLabelPosition())
    {
      case FormElement::LABEL_BEFORE:
        return '{{label}} {{input}}<hr/>';
      case FormElement::LABEL_AFTER:
        return '{{input}} {{label}}';
      case FormElement::LABEL_NONE:
      default:
        return '{{input}}';
    }
  }

  public function definitionListTemplate(FormElement $element)
  {
    switch($element->getLabelPosition())
    {
      case FormElement::LABEL_BEFORE:
        return '<dt>{{label}}</dt><dd>{{input}}</dd>';
      case FormElement::LABEL_AFTER:
        return '<dd>{{input}}</dd><dt>{{label}}</dt>';
      case FormElement::LABEL_NONE:
      default:
        return '<dd>{{input}}</dd>';
    }
  }

  public function render(FormElement $element)
  {
    $template = $this->_template;

    if(is_callable($template))
    {
      $template = $template($element);
    }

    if($template === null)
    {
      $template = $this->defaultTemplate($element);
    }

    if(strpos($template, '{{input}}') !== false)
    {
      switch($element->getType())
      {
        case FormElement::TEXTAREA;
          $input = $this->renderTextarea($element);
          break;
        case FormElement::SELECT;
          $input = $this->renderSelect($element);
          break;
        default:
          $input = $this->renderInput($element);
      }
      $template = str_replace('{{input}}', $input, $template);
    }
    if(strpos($template, '{{label}}') !== false)
    {
      $template = str_replace(
        '{{label}}',
        $this->renderLabel($element),
        $template
      );
    }

    return $template;
  }

  public function renderRequiredField()
  {
    return ' <span class="packaged-form-required" title="Required">*</span>';
  }

  public function renderLabel(FormElement $element)
  {
    if($element->getLabelPosition() == FormElement::LABEL_NONE)
    {
      return '';
    }

    $out = '<label for="' . $element->getOption('id') . '"';
    $out .= 'id="' . $element->getOption('id') . '-label">';
    $out .= $element->getLabel();
    if($element->getOption('required', false))
    {
      $out .= $this->renderRequiredField();
    }
    $out .= '</label>';
    return $out;
  }

  protected function _renderAttributes(
    FormElement $element, array $custom = null
  )
  {
    $attributes = [];

    $name = $element->getOption('name');
    if(!empty($name))
    {
      $attributes['name'] = $name;
    }
    $id = $element->getOption('id');
    if(!empty($id))
    {
      $attributes['id'] = $id;
    }

    $attributes = array_merge(
      $attributes,
      (array)$custom,
      (array)$element->getAttributes()
    );

    $return = '';
    foreach($attributes as $k => $v)
    {
      $return .= $v === null ? " $k" : " $k=\"$v\"";
    }
    return $return;
  }

  public function renderInput(FormElement $element)
  {
    $value = Strings::escape($element->getValue());
    if($element->getType() === FormElement::PASSWORD)
    {
      $value = "";
    }

    $out = '<input';
    $out .= $this->_renderAttributes(
      $element,
      [
        "type"  => $element->getType(),
        "value" => $value,
      ]
    );
    $out .= '/>';
    return $out;
  }

  public function renderTextarea(FormElement $element)
  {
    $out = '<textarea';
    $out .= $this->_renderAttributes($element);
    $out .= '>';
    $out .= Strings::escape($element->getValue());
    $out .= '</textarea>';
    return $out;
  }

  public function renderSelect(FormElement $element)
  {
    $out = '<select';
    $out .= $this->_renderAttributes($element);
    $out .= '>';

    $values = $element->getOption('values', []);
    foreach($values as $key => $val)
    {
      $disabled = false;
      if(is_array($val))
      {
        if(Arrays::isAssoc($val))
        {
          $disabled = isset($val['disabled']);
          if(isset($val['id']))
          {
            $value = $val['id'];
          }
          else if(isset($val['value']))
          {
            $value = $val['value'];
          }
          else
          {
            $value = reset($val);
          }

          if(isset($val['text']))
          {
            $text = $val['text'];
          }
          else if(isset($val['display']))
          {
            $text = $val['display'];
          }
          else
          {
            $text = current($val);
          }
        }
        else
        {
          $value = current($val);
          $text = next($val);
        }
      }
      else
      {
        $text = $val;
        $value = $key;
      }
      $out .= '<option value="' . $value . '"';

      if(is_array($element->getValue()))
      {
        if(in_array((string)$value, $element->getValue()))
        {
          $out .= ' selected="selected"';
        }
      }
      else
      {
        if((string)($element->getValue()) === (string)$value)
        {
          $out .= ' selected="selected"';
        }
      }

      if($disabled)
      {
        $out .= ' disabled';
      }
      $out .= '>';
      $out .= $text;
      $out .= '</option>';
    }

    $out .= '</select>';
    return $out;
  }
}
