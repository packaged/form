<?php
namespace PackagedUi\Form\Decorators;

use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Helpers\Arrays;
use PackagedUi\Form\Decorators\Interfaces\Decorator;

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
    $this->setAttribute('id', $id);
    return $this;
  }

  public function setAttribute($name, $value)
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
}
