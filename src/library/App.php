<?php
// +----------------------------------------------------------------------
// | jd
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;


/**
 * @method static App root_path(string $value = null)
 * @method static App app_path(string $value = null)
 * @method static App route(array $value = [])
 * @method static App route_info(string $value = null)
 */
class App
{
    private static $app = [];


    /**
     * @param $key
     * @param $params
     * @return mixed|null
     */
    public static function __callstatic($key, $params) {
        if (isset($params[0]))
            self::$app[$key] = $params[0];
        else
            return isset(self::$app[$key]) ? self::$app[$key] : null;
    }
}