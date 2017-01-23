<?php
/**
 * 封装MYSQL基本操作
 */

class Sql {
  private $conn = NULL;
  private $conf = NULL;

  protected function __construct() {
    $this->conf = Config::getIns();
    $this->connect(
      $this->conf->DB_HOST,
      $this->conf->DB_USER,
      $this->conf->DB_PWD,
      $this->conf->DB_NAME
    );
    $this->setEncoding();
  }

  protected function connect($host, $user, $pwd, $db) {
    $this->conn = mysqli_connect($host, $user, $pwd, $db);

    if (!$this->conn)
      exit("数据库连接失败");
  }

  protected function close() {
    mysqli_close($this->conn);
  }

  protected function setEncoding() {
    $this->query("set names " . $this->conf->DB_ENCODING);
  }

  protected function query($sql) {
    return mysqli_query($this->conn, $sql);
  }

  protected function findOne($sql) {
    $res = $this->query($sql);
    $row = mysqli_fetch_assoc($res);
    mysqli_free_result($res);

    return $row;
  }

  protected function findAll($sql) {
    $res = $this->query($sql);
    $rows = array();

    while ($row = mysqli_fetch_array($res)) {
      $rows[] = $row;
    }

    mysqli_free_result($res);

    return $rows;
  }

  protected function autoExecute($table, $action="insert", $data, $where = " where 1;") {
    if ($action === "insert") {
      $sql = $action . " into " . $table;
      $keys = array_keys($data);
      $values = array_values($data);
      $sql .= "(". implode(",", $keys) .") values('". implode("','", $values) ."');";
    } else if ($action === "update") {
      $sql = $action . " " . $table;
      $update_sql = " ";

      foreach ($data as $k => $v) {
        $update_sql .= $k . "=" . $v . ",";
      }

      $sql .= rtrim($update_sql, ",") . " " . $where;
    } else if ($action === "delete") {
      $sql = $action . " from " .$table . " " . $where;
    }

    $this->query($sql);

    return mysqli_affected_rows($this->conn) > 0;
  }
}