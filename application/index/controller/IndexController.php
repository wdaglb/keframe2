<?php
// +----------------------------------------------------------------------
// | jd
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace app\index\controller;


use ke\Route;

class IndexController
{
    public function index()
    {
        return Route::url(IndexController::class . '@index', ['id'=>123]);
    }


    public function article()
    {
        var_dump(Route::url('index/IndexController@article', ['id'=>123, 'page'=>1]));
        var_dump(Route::url('index/IndexController@article', ['id'=>123, 'page'=>2]));
        var_dump(Route::url('index/IndexController@article', ['id'=>123, 'page'=>3]));
        var_dump(Route::url('index/IndexController@article', ['id'=>123, 'page'=>1]));
    }

}