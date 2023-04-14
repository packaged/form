<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Form\Input\LabeledInput;
use Packaged\Form\Input\MultiInputContainer;
use Packaged\Form\Validators\HandlerEnumValidator;
use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Glimpse\Tags\Form\Label;
use Packaged\Glimpse\Tags\Form\Option;
use Packaged\Glimpse\Tags\Form\Select;
use Packaged\Helpers\Strings;
use Packaged\Ui\Html\HtmlElement;

class EnumDataHandler extends AbstractDataHandler
{
  const INPUT_STYLE_COMBINED = 'single';
  const INPUT_STYLE_SPLIT    = 'split';

  protected $_options = [];
  protected $_inputStyle = self::INPUT_STYLE_COMBINED;

  public function __construct(array $options = null)
  {
    if($options !== null)
    {
      $this->setOptions($options);
    }
  }

  public function styleSplit()
  {
    $this->_inputStyle = self::INPUT_STYLE_SPLIT;
    return $this;
  }

  public function styleCombined()
  {
    $this->_inputStyle = self::INPUT_STYLE_COMBINED;
    return $this;
  }

  public function setOptions(array $value)
  {
    $this->_options = $value;
    return $this;
  }

  public function addOption($value, $display = null)
  {
    $this->_options[$value] = $display ?? $value;
    return $this;
  }

  public function getOptions()
  {
    return $this->_options;
  }

  protected function _setupValidators()
  {
    $this->addValidator(new HandlerEnumValidator($this));
  }

  protected function _inputType()
  {
    return Input::TYPE_RADIO;
  }

  protected function _inputName()
  {
    return $this->getName();
  }

  protected function _generateSplitInput($key, $value, $selected = false): HtmlElement
  {
    $subId = Strings::pattern('XX00-XX0');
    $radio = Input::create()->setType($this->_inputType());
    $radio->addAttributes(
      [
        'name'  => $this->_inputName(),
        'id'    => $this->getId() . $subId,
        'value' => $key,
      ]
    );
    if($selected)
    {
      $radio->setAttribute('checked', true);
    }
    return new LabeledInput($radio, Label::create($value)->setAttribute('for', $this->getId() . $subId));
  }

  protected function _isSelectedOption($option): bool
  {
    return $option == $this->getValue();
  }

  protected function _generateInput(): HtmlElement
  {
    if($this->_inputStyle === self::INPUT_STYLE_SPLIT)
    {
      $ele = new MultiInputContainer();
      foreach($this->getOptions() as $optK => $optV)
      {
        $ele->addInput($this->_generateSplitInput($optK, $optV, $this->_isSelectedOption($optK)));
      }
      return $ele;
    }

    $options = [];
    foreach($this->getOptions() as $optK => $optV)
    {
      $options[] = Option::create($optV)->addAttributes(
        array_filter(
          [
            'value'    => $optK,
            'selected' => $this->_isSelectedOption($optK),
          ]
        )
      );
    }
    $ele = Select::create($options);
    $ele->addAttributes(
      [
        'name' => $this->_inputName(),
        'id'   => $this->getId(),
      ]
    );
    return $ele;
  }

  public function getInputClass(): string
  {
    return parent::getInputClass() . '-' . $this->_inputStyle;
  }

}
