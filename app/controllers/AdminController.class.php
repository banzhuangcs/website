<?php
final class AdminController extends Controller {
  private $admin_model = NULL;

  public function __construct() {
    parent::__construct();

    $this->admin_model = new AdminModel();
  }

  public function index($title, $content) {
    $admin_view = new View($this->name, "index");
    $admin_view->assign(array("title" => $title, "content" => $content));
    $admin_view->render();
  }

  public function doReg($username, $password, $nickname, $telphone, $email) {
    $affected_rows = $this->admin_model->add(array(
      "username" => htmlentities(urldecode($username)),
      "password" => htmlentities(urldecode($password)),
      "nickname" => htmlentities(urldecode($nickname)),
      "telphone" => htmlentities(urldecode($telphone)),
      "email" => htmlentities(urldecode($email))
    ));

    echo $affected_rows ? "成功" : "失败";
  }
}