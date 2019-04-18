<?php
namespace Packaged\Form\Decorators;

use Exception;
use Packaged\Form\Decorators\Interfaces\Decorator;
use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Helpers\Arrays;
use Packaged\SafeHtml\SafeHtml;

abstract class AbstractDecorator implements Decorator
{
  protected $_attributes = [];

  /**
   * @return string
   */
  public function getId(): ?string
  {
    return $this->getAttribute('id');
  }

  /**
   * @param string $id
   *
   * @return $this
   */
  public function setId($id)
  {
    $this->addAttribute('id', $id);
    return $this;
  }

  public function addAttribute($name, $value)
  {
    $this->_attributes[$name] = $value;
    return $this;
  }

  public function getAttribute($name): ?string
  {
    return Arrays::value($this->_attributes, $name);
  }

  public function removeAttribute($name)
  {
    unset($this->_attributes[$name]);
    return $this;
  }

  public function hasAttribute($name): bool
  {
    return array_key_exists($name, $this->_attributes);
  }

  /**
   * @param HtmlTag $ele
   *
   * @return HtmlTag $ele
   */
  protected function _hydrateElement(HtmlTag $ele)
  {
    return $ele->setAttributes($this->_attributes);
  }

  /**
   * @return HtmlTag
   */
  abstract protected function _getElement();

  /**
   * @return string
   * @throws Exception
   */
  public function render(): string
  {
    return (string)$this->produceSafeHTML();
  }

  /**
   * @return SafeHtml
   * @throws Exception
   */
  public function produceSafeHTML(): SafeHtml
  {
    return $this->_getElement()->produceSafeHTML();
  }
}
