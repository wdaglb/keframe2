<?php
// +----------------------------------------------------------------------
// | jd
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


use ke\Route;


Route::group('index', function () {
    Route::get('/', 'IndexController@index');


    Route::get('/article/[id:n]', 'IndexController@article')->as('index/article');

});



Route::get('/index', 'IndexController@index');


