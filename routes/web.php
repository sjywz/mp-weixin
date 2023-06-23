<?php

use App\Http\Controllers\Authroize;
use App\Http\Controllers\PlatformController;
use App\Services\AutoRule;
use Illuminate\Support\Facades\DB;
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
    $replyRule = DB::table('auto_reply')
            ->where('id',8)
            ->select(['id','key','key','event','context'])
            ->orderBy('wight','desc')
            ->first();
    $appid = 'wx088ac82f8a915d8c';
    if($replyRule && $replyRule->context){
        $replyContext = json_decode($replyRule->context,true);
        $replyList = AutoRule::buildContext($appid, $replyContext);
        print_r($replyList);die;
    }
});
