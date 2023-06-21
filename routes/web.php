<?php

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

Route::get('/', function () {
    return 'welcome';
});

Route::get('/test', function(){
    $replyRule = DB::table('auto_reply')
            ->where('id',1)
            ->select(['id','key','key','event','context'])
            ->orderBy('wight','desc')
            ->first();

    if($replyRule){
        $replyContext = json_decode($replyRule->context,true);
        $replyList = AutoRule::buildContext($replyContext);
        print_r($replyList);
    }
});
