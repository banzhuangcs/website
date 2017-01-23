<?php
/**
 * 继承Sql类，根据model类名得到数据表名
 */

class Model extends Sql {
  protected $table = NULL;
  private $conf = NULL;

  protected function __construct() {
    parent::__construct();

    $this->conf = Config::getIns();
    $this->table = $this->conf->DB_PREFIX . substr(lcfirst(get_class($this)), 0, -5);
  }
}