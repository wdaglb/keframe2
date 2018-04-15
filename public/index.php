<?php
// +----------------------------------------------------------------------
// | jd
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


define('ROOT', __DIR__ . '/');

require ROOT . 'vendor/autoload.php';


$module = $_GET['m'] ?? 'index';
$controller = $_GET['c'] ?? 'index';
$action = $_GET['a'] ?? 'index';


$class = "app\{$module}\{$controller}";
if (!class_exists($class))
    $app = new $class();

if (!method_exists($app, $action)) {

}

