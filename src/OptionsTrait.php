<?php
namespace Packaged\Form;

trait OptionsTrait
{
  /**
   * @var array Options
   */
  protected $_options = [];

  public function addOptions(array $options)
  {
    $this->_options = array_merge($this->_options, $options);
    return $this;
  }

  public function setOptions(array $options)
  {
    $this->_options = $options;
    return $this;
  }

  public function setOption($key, $value)
  {
    $this->_options[$key] = $value;
    return $this;
  }

  public function deleteOption($key)
  {
    unset($this->_options[$key]);
    return $this;
  }

  public function getOptions()
  {
    return $this->_options;
  }

  public function getOption($key, $default = null)
  {
    return isset($this->_options[$key]) ? $this->_options[$key] : $default;
  }
}
