<?php
/**
 * 记录分配给模板的数据，根据控制器对应模板的父目录，action对应模板文件，然后渲染html
 */

class View {
  protected $controller = "";
  protected $action = "";
  protected $varibles = array();
  protected $dir = "templates/";

  public function __construct($controller, $action) {
    $this->controller = lcfirst($controller);
    $this->action = lcfirst($action);
  }

  public function assign($value) {
    foreach ($value as $k => $v)
      $this->varibles[$k] = $v;
  }

  public function render() {
    $app_view_path = APP_PATH . $this->dir . $this->controller . "/" . $this->action . ".html";
    $admin_view_path = ADMIN_PATH . $this->dir . $this->controller . "/" . $this->action . ".html";
    extract($this->varibles);

    if (is_file($app_view_path))
      require $app_view_path;
    else if (is_file($admin_view_path))
      require $admin_view_path;
    else
      exit("不存在该模板");
  }
}