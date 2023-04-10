<?php

namespace App\Http\Controllers;

use App\Models\MpMessage;
use App\Models\PlatformEvent;
use App\Models\Mp;
use App\Services\PlatformService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlatformController extends Controller
{
    public function auth($id)
    {
        Log::info('Platform event', ['id'=>$id]);
        try{
            $plat = new PlatformService();
            $app = $plat->getApp($id);
            $server = $app->getServer();

            $server->with(function($message, \Closure $next) {
                $infoType = $message->InfoType;
                $appId = $message->AppId;
                $createTime = $message->CreateTime;
                $AuthorizerAppid = $message->AuthorizerAppid;

                if($infoType === 'component_verify_ticket'){

                }else if($infoType === 'authorized'){

                }else if($infoType === 'updateauthorized'){

                }else if($infoType === 'unauthorized'){

                }else if($infoType === 'notify_third_fasteregister'){

                }

                PlatformEvent::create([
                    'appid' => $appId,
                    'create_time' => $createTime,
                    'info_type' => $infoType,
                    'rest' => json_encode(collect($message)->toArray())
                ]);
                return $next($message);
            });

            return $server->serve();
        }catch(\Exception $e){
            Log::warning('Platform message', ['id'=>$id,'err'=>$e->getMessage()]);
        }
    }

    public function msg($id,$appid)
    {
        Log::info('Platform message', ['id'=>$id,'appid'=>$appid]);
        try{
            $plat = new PlatformService();
            $app = $plat->getApp($id);
            $server = $app->getServer();

            $server->with(function($message, \Closure $next) use ($appid){
                $msgType = $message->MsgType;
                $msgId = $message->MsgId;
                $createTime = $message->CreateTime;
                $openid = $message->FromUserName;
                $to = $message->ToUserName;
                $event = $message->Event;

                MpMessage::create([
                    'type' => $msgType,
                    'msgid' => $msgId,
                    'create_time' => $createTime,
                    'appid' => $appid,
                    'from' => $openid,
                    'to' => $to,
                    'event' => $event,
                    'rest' => json_encode([
                        'test' => collect($message)->toArray(),
                    ])
                ]);
                return $next($message);
            })->with(function($message, \Closure $next) use ($appid){
                $type = 'text';
                $content = '你好';
                $openid = $message->FromUserName;
                $to = $message->ToUserName;
                $time = time();

                $reply = [
                    'MsgType' => $type,
                    'Content' => $content,
                ];

                MpMessage::create([
                    'type' => $type,
                    'msgid' => uniqid(),
                    'create_time' => $time,
                    'appid' => $appid,
                    'from' => $to,
                    'to' => $openid,
                    'rest' => json_encode($reply)
                ]);

                return $reply;
            });

            return $server->serve();
        }catch(\Exception $e){
            Log::warning('Platform message', ['id'=>$id,'appid'=>$appid,'err'=>$e->getMessage()]);
        }
    }
}
