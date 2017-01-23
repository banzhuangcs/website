<?php
/**
  mvc框架入口文件，功能包括：
  1、路由解析，截取url，解析得到controller和action;
  2、过滤参数非法字符，' " \ html标签 防止插入数据库语法错误和xss攻击;
  3、检测开发环境，如果是启动了调试，则在页面中放出错误代码，如果正式环境，则启动写入日志;
  4、自动加载controller、model、框架核心类;
**/

final class AppFrame {

  public function __construct() {
    $this->initialPath();
  }

  /**
   * 启动
   */
  public function start() {
    spl_autoload_register(array($this, "_autoloadClass"));

	$this->route();
	$this->addslashes();
	$this->setReporting();
  }

  private function initialPath() {
    defined("ROOT") or define("ROOT", $_SERVER["SCRIPT_FILENAME"] . "/");
    defined("APP_PATH") or define("APP_PATH", ROOT . "app/");
    defined("ADMIN_PATH") or define("ADMIN_PATH", ROOT . "admin/");
    define("FRAME_PATH", str_replace("\\", "/", dirname(__FILE__)) . "/");

    require FRAME_PATH . "lib/Config.class.php";
    require FRAME_PATH . "lib/Sql.class.php";
    require FRAME_PATH . "lib/Controller.class.php";
    require FRAME_PATH . "lib/Model.class.php";
    require FRAME_PATH . "lib/View.class.php";
  }

  /**
   * 路由处理
   */
  private function route() {
    // 截取url，从url的pathname中提取出controller和action
    $url = $_SERVER["HTTP_HOST"] . addslashes($_SERVER["REQUEST_URI"]);
    $url_info = parse_url($url);

    if (!empty($url_info["query"]))
      parse_str($url_info["query"], $params);
    else
      $params = array("c" => "index", "a" => "index");

    $controller = ucfirst(array_shift($params) . "Controller");
    $action = lcfirst(array_shift($params));

    // 判断包含controller类的文件是否存在
    if (!is_file($controller_filename = APP_PATH . "controllers/" . $controller . ".class.php"))
      exit("找不到". $controller ."控制器");

    // 引入controller类
    require $controller_filename;

    if (!method_exists($controller, $action))
      exit("找不到". $action ."方法");

    // 创建controller实例，调用实例的action方法
    $ins = new $controller;
    call_user_func_array(array($ins, $action), $params);
  }

  private function addslashes() {
    $_GET = $this->_addslashes($_GET);
    $_POST = $this->_addslashes($_POST);
    $_COOKIE = $this->_addslashes($_COOKIE);
  }

  private function _addslashes($arr) {
    return is_array($arr)
      ? array_map(array($this, "_addslashes"), $arr)
      : addslashes($arr);
  }

  private function setReporting() {
    if (defined("APP_DEBUG") && APP_DEBUG === true) {
      error_reporting(E_ALL);
      ini_set("display_errors", "On");
      ini_set("log_errors", "Off");
    } else {
      ini_set("display_errors", "Off");
      ini_set("log_errors", "On");
      ini_set("error_log", FRAME_PATH . "runtime/log/error.log");
    }
  }

  private function _autoloadClass($class) {
    $controller_path = APP_PATH . "controllers/" . ucfirst($class) . ".class.php";
    $model_path = APP_PATH . "models/" . ucfirst($class) . ".class.php";
    $lib_path = ROOT . "lib/" . lcfirst($class) . ".class.php";

    if (is_file($controller_path))
      require $controller_path;
    else if (is_file($model_path))
      require $model_path;
    else if (is_file($lib_path))
      require $lib_path;
    else
      exit("不存在". $class ."类的路径");
  }
}