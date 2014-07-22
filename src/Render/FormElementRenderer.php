<?php
namespace Packaged\Form\Render;

use Packaged\Form\FormElement;

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
      case FormElement::LABEL_NONE:
        return '{{input}}';
      case FormElement::LABEL_BEFORE:
        return '{{label}} {{input}}<hr/>';
      case FormElement::LABEL_AFTER:
        return '{{input}} {{label}}';
    }
  }

  public function definitionListTemplate(FormElement $element)
  {
    switch($element->getLabelPosition())
    {
      case FormElement::LABEL_NONE:
        return '<dd>{{input}}</dd>';
      case FormElement::LABEL_BEFORE:
        return '<dt>{{label}}</dt><dd>{{input}}</dd>';
      case FormElement::LABEL_AFTER:
        return '<dd>{{input}}</dd><dt>{{label}}</dt>';
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

    $out = str_replace('{{input}}', $input, $template);
    $out = str_replace('{{label}}', $this->renderLabel($element), $out);

    return $out;
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
    $attributes = [
      "name" => $element->getOption('name'),
      "id"   => $element->getOption('id')
    ];

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
    $value = esc($element->getValue());
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
    $out .= esc($element->getValue());
    $out .= '</textarea>';
    return $out;
  }

  public function renderSelect(FormElement $element)
  {
    $out = '<select';
    $out .= $this->_renderAttributes($element);
    $out .= '>';

    foreach($element->getOption('values', []) as $val)
    {
      if(is_array($val))
      {
        if(is_assoc($val))
        {
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
          $text  = next($val);
        }
      }
      else
      {
        $text = $value = $val;
      }
      $out .= '<option value="' . $value . '"';
      if((string)($element->getValue()) === (string)$value)
      {
        $out .= ' selected="selected"';
      }
      $out .= '>';
      $out .= $text;
      $out .= '</option>';
    }

    $out .= '</select>';
    return $out;
  }
}
