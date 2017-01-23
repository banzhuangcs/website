<?php
/**
 * 调度model得到数据，传入view渲染
 */

class Controller {
  protected $name = "";

  protected function __construct() {
    $this->name = substr(lcfirst(get_class($this)), 0, -10);
  }
}