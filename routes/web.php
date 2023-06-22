<?php

use App\Http\Controllers\Authroize;
use App\Http\Controllers\PlatformController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('/mp/{id}',[PlatformController::class, 'mp']);
Route::any('/platauth/{id}',[PlatformController::class, 'auth']);
Route::any('/platmsg/{id}/{appid}',[PlatformController::class, 'msg']);

Route::get('/authroize/{appid}',[Authroize::class, 'index']);
Route::get('/authroize/call/{appid}',[Authroize::class, 'call']);


Route::get('/', function () {
    return 'welcome';
});

Route::get('/test', function(){
    echo 'this is test';
});
