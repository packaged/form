<?php
namespace Packaged\Form\Decorators\Interfaces;

use Packaged\SafeHtml\ISafeHtmlProducer;
use Packaged\Ui\Renderable;

/**
 * Decorators are responsible for creating elements
 *
 * @package Packaged\Form
 */
interface Decorator extends Renderable, ISafeHtmlProducer
{
  /**
   * @return string
   */
  public function getId(): ?string;

  /**
   * @param string $id
   *
   * @return $this
   */
  public function setId($id);

  /**
   * @param string $name
   * @param string $value
   *
   * @return $this
   */
  public function addAttribute($name, $value);

  /**
   * @param string $name
   *
   * @return string
   */
  public function getAttribute($name): ?string;

  /**
   * @param string $name
   *
   * @return $this
   */
  public function removeAttribute($name);

  /**
   * @param string $name
   *
   * @return bool
   */
  public function hasAttribute($name): bool;
}
