<?php
// +----------------------------------------------------------------------
// | jd
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;


class Config
{
    private static $data = [];

    /**
     * 加载配置
     * @param string $node
     * @throws \Exception
     */
    public static function load($node)
    {
        $path = App::root_path() . 'config/' . $node . '.php';
        if (!is_file($path)) {
            throw new \Exception('Config File Not Exist:' . $node);
        }
        Config::set($node, require($path));
    }

    /**
     * 设置配置
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        self::$data[$key] = $value;
    }


    /**
     * 获取配置
     * @param string $key
     * @return mixed|null
     */
    public static function get($key = '')
    {
        if ($key == '') return self::$data;
        return isset(self::$data[$key]) ? self::$data[$key] : null;
    }


}