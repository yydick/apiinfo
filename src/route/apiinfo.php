<?php

use Illuminate\Support\Facades\Route;

Route::prefix('apiinfo')->group(function () {
    Route::get('/', 'Spool\ApiInfo\Controllers\ApiInfoController@index');
    Route::get('/contents', 'Spool\ApiInfo\Controllers\ApiInfoController@contents');
    Route::get('/welcome', 'Spool\ApiInfo\Controllers\ApiInfoController@welcome');
    Route::get('/search', 'Spool\ApiInfo\Controllers\ApiInfoController@search');
});
Route::group(
    [
        "namespace" => "Spool\ApiInfo\Controllers",
        "prefix" => "exampleApiinfo"
    ],
    function () {
        //做版本控制
        //跨域
        Route::options('/{all}', function (Request $request) {
            $origin = $request->header('ORIGIN', '*');
            header("Access-Control-Allow-Origin: $origin");
            header("Access-Control-Allow-Credentials: true");
            header('Access-Control-Allow-Methods: POST, OPTIONS');
            header('Access-Control-Allow-Headers: Origin, Access-Control-Request-Headers, SERVER_NAME, Access-Control-Allow-Headers, cache-control, token, X-Requested-With, Content-Type, Accept, Connection, User-Agent, Cookie');
        })->where(['all' => '([a-zA-Z0-9-]|/)+']);
        Route::group([
            // "namespace" => "V1"
        ], function () { //第一版本
            // Controllers Within The "AppHttpControllersApiV1" Namespace
            //用户相关

            Route::group([
                // "middleware" => ""
            ], function () {
                //需要api认证的路由（用户表必须有api_token字段）
                //在控制器中获取用户信息$user = $request->user();
                //用户获取个人资料信息
                Route::get(
                    'getM',
                    'ExampleController@getM'
                )->name('api.v1.index.getM');
                Route::post(
                    'postM',
                    'ExampleController@postM'
                )->name('api.v1.index.postM');
                Route::put(
                    'putM',
                    'ExampleController@putM'
                )->name('api.v1.index.putM');
                Route::delete(
                    'deleteM',
                    'ExampleController@deleteM'
                )->name('api.v1.index.deleteM');
                Route::any(
                    'anyM',
                    'ExampleController@anyM'
                )->name('api.v1.index.anyM');
            });
        });
    }
);
