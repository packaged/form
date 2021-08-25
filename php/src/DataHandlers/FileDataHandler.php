<?php
namespace Packaged\Form\DataHandlers;

use Packaged\Glimpse\Tags\Form\Input;
use Packaged\Ui\Html\HtmlElement;

class FileDataHandler extends AbstractDataHandler
{
  public function getFileName()
  {
    return $this->_value['name'] ?? null;
  }

  public function getFileType()
  {
    return $this->_value['type'] ?? null;
  }

  public function getFileSize()
  {
    return $this->_value['size'] ?? null;
  }

  /**
   * @link https://www.php.net/manual/en/features.file-upload.errors.php
   */
  public function getErrorCode()
  {
    return $this->_value['error'] ?? null;
  }

  public function getFileLocation()
  {
    return $this->_value['tmp_name'] ?? null;
  }

  public function hasUpload()
  {
    return isset($this->_value['tmp_name']) && !empty($this->_value['tmp_name']);
  }

  protected function _createBaseElement(): HtmlElement
  {
    return Input::create()->setType(Input::TYPE_FILE);
  }

  protected function _generateInput(): HtmlElement
  {
    $ele = $this->_createBaseElement();
    $ele->addAttributes(
      [
        'name'        => $this->getName(),
        'id'          => $this->getId(),
        'placeholder' => $this->getPlaceholder(),
      ]
    );
    return $ele;
  }

}
