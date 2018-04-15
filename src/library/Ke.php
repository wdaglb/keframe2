<?php
// +----------------------------------------------------------------------
// | jd
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;



use Illuminate\Database\Capsule\Manager;

class Ke
{

    public static function bootstrap($app_path)
    {
        $root_path = $app_path . '../';
        // 定义根目录
        App::root_path($root_path);
        App::app_path($app_path);

        spl_autoload_register(function ($class) {
            if (substr($class, 0, 4) == 'app\\') {
                $file = App::app_path() . str_replace(['app\\', '\\'], ['', '/'], $class) . '.php';
                if (!is_file($file))
                    return false;
                require $file;
            }
            return false;
        });

        // 程序配置文件加载
        foreach (glob($root_path . 'config/*.php') as $filename)
            Config::load(str_replace('.php', '', basename($filename)));

        // 初始化ORM
        self::init_orm();


        // 应用目录文件加载
        foreach (glob($root_path . '*.php') as $filename)
            require_once $filename;


        // 路由配置文件加载
        foreach (glob($root_path . 'route/*.php') as $filename)
            require_once $filename;
        Route::dispatch();
    }


    private static function init_orm()
    {
        $manage = new Manager();
        $manage->addConnection(Config::get('database'));
        $manage->bootEloquent();
    }

}