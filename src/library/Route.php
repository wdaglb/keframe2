<?php
// +----------------------------------------------------------------------
// | jd
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;
use ke\route\RouteItem;


/**
 * @method static RouteItem get(string $route, string $controller, string $action = 'index')
 * @method static RouteItem post(string $route, string $controller, string $action = 'index')
 * @method static RouteItem put(string $route, string $controller, string $action = 'index')
 * @method static RouteItem delete(string $route, string $controller, string $action = 'index')
 * @method static RouteItem options(string $route, string $controller, string $action = 'index')
 */
class Route
{
    /**
     * @var RouteItem
     */
    private static $route;

    private static $groupName;

    public static function __callstatic($method, $params) {
        if (!self::$route) {
            self::$route = new RouteItem();
        }
        return self::$route->register(array(
            'rule'=>$params[0],
            'class'=>self::$groupName . $params[1],
            'method'=>strtoupper($method)
        ));
    }


    public static function group($name, callable $callback)
    {
        self::$groupName = $name . '/';
        call_user_func($callback);
        self::$groupName = '';
    }


    /**
     * 生成URL
     * @param string $rule
     * @param array $param
     * @return mixed
     */
    public static function url($rule, array $param = [])
    {
        return self::$route->url($rule, $param);
    }


    /**
     * 引导服务启动
     */
    public static function dispatch()
    {
        self::$route->dispatch();
    }
}