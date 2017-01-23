<?php
/**
 * 读取配置参数
 */

final class Config {
  private static $ins = NULL;
  private $data = NULL;

  protected function __construct() {
    $this->data = $GLOBALS["CFG"];
  }

  public static function getIns() {
    if (!(self::$ins instanceof self))
      self::$ins = new self();

    return self::$ins;
  }

  public function __get($key) {
    return isset($this->data[$key])
      ? $this->data[$key]
      : "";
  }
}