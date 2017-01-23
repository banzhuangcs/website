<?php
class AdminModel extends Model {
  public function __construct() {
    parent::__construct();
  }

  public function add($params) {
    return $this->autoExecute($this->table, "insert", $params);
  }
}