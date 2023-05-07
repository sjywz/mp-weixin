<?php

namespace App\Http\Controllers;

use App\Models\Mp;
use App\Services\MsgService;
use App\Services\WeixinService;
use App\Services\ReplyService;
use Illuminate\Support\Facades\Log;

class PlatformController extends Controller
{
    public function mp($id)
    {
        try{
            $weixin = new WeixinService();
            $app = $weixin->getApp($id,true);
            $server = $app->getServer();

            $account = $app->getAccount();
            $appid = $account->getAppId();

            $server->with(function($message, \Closure $next){
                return $next($message);
            })->with(function($message, \Closure $next) use ($appid){
                MsgService::falsh($message,$appid);
                return $next($message);
            })->with(function($message) use ($appid) {
                return ReplyService::handle($appid,$message);
            });

            return $server->serve();
        }catch(\Exception $e){
            Log::warning('MP message', [
                'id'=>$id,
                'err'=>$e->getMessage(),
            ]);
        }
    }

    public function msg($id,$appid)
    {
        try{
            $weixin = new WeixinService();
            $app = $weixin->getApp($id);
            $server = $app->getServer();

            $account = $app->getAccount();
            $platAppid = $account->getAppId();

            $server->with(function($message, \Closure $next){
                $msgType = $message->MsgType;
                $content = $message->Content;
                $testMsgText = 'TESTCOMPONENT_MSG_TYPE_TEXT';
                if($msgType === 'text' && $content === $testMsgText){
                    return $testMsgText.'_callback';
                }
                return $next($message);
            })->with(function($message, \Closure $next) use ($appid,$platAppid){
                MsgService::falsh($message,$appid,$platAppid);
                return $next($message);
            })->with(function($message) use ($appid,$platAppid){
                return ReplyService::handle($appid,$message,$platAppid);
            });

            return $server->serve();
        }catch(\Exception $e){
            Log::warning('Platform message', [
                'id'=>$id,
                'appid'=>$appid,
                'err'=>$e->getMessage()
            ]);
        }
    }

    public function auth($id)
    {
        try{
            $weixin = new WeixinService();
            $app = $weixin->getApp($id);
            $server = $app->getServer();

            $account = $app->getAccount();
            $platAppid = $account->getAppId();

            $server->with(function($message, \Closure $next) use ($platAppid) {
                $infoType = $message->InfoType;
                $aAppid = $message->AuthorizerAppid;

                if($infoType === 'unauthorized'){
                    Mp::where('appid',$aAppid)->where('plat_appid',$platAppid)->delete();
                }else if($infoType === 'notify_third_fasteregister'){

                }else if($infoType === 'component_verify_ticket'){

                }else if($infoType === 'authorized'){

                }else if($infoType === 'updateauthorized'){

                }else{

                }
                return $next($message);
            })->with(function($message, \Closure $next) use ($platAppid) {
                MsgService::flashPlatEvent($message,$platAppid);
                return $next($message);
            });

            return $server->serve();
        }catch(\Exception $e){
            Log::warning('Platform message', [
                'id'=>$id,
                'err'=>$e->getMessage()
            ]);
        }
    }
}
