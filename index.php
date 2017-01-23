<?php
date_default_timezone_set("Asia/Shanghai");

define("ROOT", str_replace("\\", "/", dirname(__FILE__)) . "/");
define("APP_PATH", ROOT . "app/");
define("ADMIN_PATH", ROOT . "admin/");
define("APP_DEBUG", true);

require ROOT . "config/config.inc.php";
require ROOT . "frame/init.php";

$app_frame = new AppFrame();
$app_frame->start();

