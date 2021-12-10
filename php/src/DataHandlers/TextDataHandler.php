<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Helpers\Strings;
use Packaged\Helpers\ValueAs;
use Packaged\Ui\Html\HtmlElement;

class TextDataHandler extends AbstractDataHandler
{
  protected $_autocomplete = '';

  public function getAutocomplete(): string
  {
    return $this->_autocomplete;
  }

  public function setAutocomplete(string $autocomplete): self
  {
    $this->_autocomplete = $autocomplete;
    return $this;
  }

  public function formatValue($value)
  {
    Strings::stringable($value);
    return parent::formatValue(ValueAs::string($value));
  }

  protected function _createBaseElement(): HtmlElement
  {
    return new Input();
  }

  protected function _generateInput(): HtmlElement
  {
    $ele = $this->_createBaseElement();
    $ele->addAttributes(
      [
        'name'        => $this->getName(),
        'id'          => $this->getId(),
        'value'       => $this->formatValue($this->getValue()),
        'placeholder' => $this->getPlaceholder(),
      ]
    );

    $autocomplete = $this->getAutocomplete();
    if(!empty($autocomplete))
    {
      $ele->setAttribute('autocomplete', $autocomplete);
    }

    return $ele;
  }

}
