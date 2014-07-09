<?php
namespace Packaged\Form;

trait OptionsTrait
{
  /**
   * @var array Options
   */
  protected $_options = ['attributes' => []];

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

  public function addSubOption($key, $arrayKey, $value)
  {
    $this->_options[$key][$arrayKey] = $value;
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

  public function setAttribute($key, $value)
  {
    $this->_options['attributes'][$key] = $value;
    return $this;
  }

  public function setAttributes(array $attributes)
  {
    $this->_options['attributes'] = $attributes;
    return $this;
  }

  public function getAttributes()
  {
    if(!isset($this->_options['attributes']))
    {
      $this->_options['attributes'] = [];
    }
    return $this->_options['attributes'];
  }

  public function getAttribute($key, $default = null)
  {
    if(isset($this->_options['attributes'][$key]))
    {
      return $this->_options['attributes'][$key];
    }
    return $default;
  }

  public function deleteAttribute($key)
  {
    unset($this->_options['attributes'][$key]);
    return $this;
  }
}
