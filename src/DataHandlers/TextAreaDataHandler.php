<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Textarea;
use Packaged\Helpers\Strings;
use Packaged\Helpers\ValueAs;
use Packaged\Ui\Html\HtmlElement;

class TextAreaDataHandler extends AbstractDataHandler
{

  protected $rows = 4;

  public function formatValue($value)
  {
    Strings::stringable($value);
    return parent::formatValue(ValueAs::string($value));
  }

  public function setRows(int $rows): TextareaDataHandler
  {
    $this->rows = $rows;
    return $this;
  }

  public function getRows(): int
  {
    return $this->rows;
  }

  protected function _createBaseElement(): HtmlElement
  {
    return new Textarea();
  }

  protected function _generateInput(): HtmlElement
  {
    $ele = $this->_createBaseElement();
    $ele->addAttributes(
      [
        'name'        => $this->getName(),
        'id'          => $this->getId(),
        'placeholder' => $this->getPlaceholder(),
        'rows'        => $this->getRows(),
      ]
    );
    if($ele instanceof Textarea)
    {
      $ele->setContent($this->formatValue($this->getValue()));
    }
    return $ele;
  }
}
