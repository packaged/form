<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Form\Validators\HandlerEnumValidator;
use Packaged\Glimpse\Tags\Form\Option;
use Packaged\Glimpse\Tags\Form\Select;
use Packaged\Ui\Html\HtmlElement;

class EnumDataHandler extends AbstractDataHandler
{
  public function __construct(array $options = null)
  {
    if($options !== null)
    {
      $this->setOptions($options);
    }
  }

  protected $_options = [];

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

  protected function _setupValidator()
  {
    $this->addValidator(new HandlerEnumValidator($this));
  }

  protected function _generateInput(): HtmlElement
  {
    $selected = $this->getValue() ?? $this->getDefaultValue();
    $options = [];
    foreach($this->getOptions() as $optK => $optV)
    {
      $options[] = Option::create($optV)->addAttributes(
        array_filter(
          [
            'value'    => $optK,
            'selected' => $optK === $selected,
          ]
        )
      );
    }
    $ele = Select::create($options);
    $ele->addAttributes(
      [
        'id'   => $this->getId(),
        'name' => $this->getName(),
      ]
    );
    return $ele;
  }
}
