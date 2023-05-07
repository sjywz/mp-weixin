<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('/mp', 'MpController');

    $router->get('/platform/auth/{id}', 'PlatformController@auth');
    $router->get('/platform/call/{id}', 'PlatformController@call');

    $router->resource('/platform', 'PlatformController');
    $router->resource('/platevent','PlatformEventController');
    $router->resource('/mpmessage','MpMessageController');
    $router->resource('/mpmenu','MpMenuController');
    $router->resource('/material','MaterialController');
});
