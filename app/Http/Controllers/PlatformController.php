<?php

namespace App\Http\Controllers;

use App\Models\MpMessage;
use App\Models\PlatformEvent;
use App\Services\PlatformService;
use Illuminate\Support\Facades\DB;

class PlatformController extends Controller
{
    public function auth($id)
    {
        DB::table('log')->insert([
            'context' => 'into,auth===>id:'.$id,
            'add_time' => date('Y-m-d H:i:s')
        ]);

        try{
            $plat = new PlatformService();
            $app = $plat->getApp($id);
            $server = $app->getServer();

            $app->setRequestFromSymfonyRequest(request());

            $server->with(function($message, \Closure $next) {
                $infoType = $message->InfoType;
                $createTime = $message->CreateTime;
                $appId = $message->AppId;

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
            DB::table('log')->insert([
                'context' => $e->getMessage(),
                'add_time' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function call($id)
    {
        $auth_code = request()->get('auth_code');
        $plat = new PlatformService();
        $app = $plat->getApp($id);
        $server = $app->getServer();
        $authorization = $app->getAuthorization($auth_code);

        $appid = $authorization->getAppId();
        $accessToken = $authorization->getAccessToken();
        $refreshToken = $authorization->getRefreshToken();

        print_r($appid);
        print_r($accessToken);
        print_r($refreshToken);

        dd($authorization->toArray());
    }

    public function msg($id,$appid)
    {
        DB::table('log')->insert([
            'context' => 'into,msg===>appid:'.$appid.'id:'.$id,
            'add_time' => date('Y-m-d H:i:s')
        ]);

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
                    'rest' => json_encode(collect($message)->toArray())
                ]);
                return $next($message);
            });

            return $server->serve();
        }catch(\Exception $e){
            DB::table('log')->insert([
                'context' => $e->getMessage(),
                'add_time' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function test($id)
    {
        $plat = new PlatformService();
        $app = $plat->getApp($id);
        $server = $app->getServer();
        $componentAccessToken = $app->getComponentAccessToken();
        $account = $app->getAccount();

        // $verifyTicket = $server->getVerfiyTicket();

        // $ticket = $verifyTicket->getTicket(); // strval

        $query = [
            'component_appid' => $account->getAppId(),
            'pre_auth_code' => '',
            'redirect_uri' => '',
            'auth_type' => '',
            'biz_appid' => '',
            'category_id_list' => '',
        ];

        $h5 = 'https://open.weixin.qq.com/wxaopen/safe/bindcomponent?action=bindcomponent&no_scan=1&%s#wechat_redirect';
        $pc = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?%s';

        $callback = 'http://yowx.p.bcode.cc/platcall/'.$id;
        $url = $app->createPreAuthorizationUrl($callback);
        echo '<pre>';
        echo '<a href="'.$url.'">开始授权</a>';
        echo $componentAccessToken->getToken();
        echo '<hr>';
        echo $account->getAppId();
        echo '<hr>';
        echo $account->getSecret();
        echo '<hr>';
        echo $account->getToken();
        echo '<hr>';
        echo $account->getAesKey();
    }
}
