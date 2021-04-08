<?php

use Illuminate\Support\Facades\Route;

Route::prefix('apiinfo')->group(function(){
    Route::get('/', 'Spool\ApiInfo\Controllers\ApiInfoController@index');
    Route::get('/contents', 'Spool\ApiInfo\Controllers\ApiInfoController@contents');
    Route::get('/test', 'Spool\ApiInfo\Controllers\ApiInfoController@test');
});
