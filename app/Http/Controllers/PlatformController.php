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
            DB::table('log')->insert([
                'context' => $e->getMessage(),
                'add_time' => date('Y-m-d H:i:s')
            ]);
        }
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

                return $content;
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

        $account = $app->getAccount();
        $pAppid = $account->getAppId();


        $server = $app->getServer();
        $authorization = $app->getAuthorization($auth_code);

        $appid = $authorization->getAppId();
        $accessToken = $authorization->getAccessToken();
        $refreshToken = $authorization->getRefreshToken();

        $authorizationInfo = $authorization->authorization_info;

        $aAppid = $authorizationInfo['authorizer_appid'];
        $aAccessToken = $authorizationInfo['authorizer_access_token'];
        $aRefreshToken = $authorizationInfo['authorizer_refresh_token'];
        $expires_in = $authorizationInfo['expires_in'];
        $func_info = $authorizationInfo['func_info'];


        $data = [
            'id' => '',
            'name' => '',
            'icon' => '',
            'appid' => '',
            'app_secret' => '',
            'verify_token' => '',
            ''
        ];
        echo '<pre>';
        print_r($appid);
        echo '<hr>';
        print_r($refreshToken);
        echo '<hr>';
        echo $pAppid;
        echo '<hr>';
        print_r($accessToken);
        echo '<hr>';
        print_r($authorization->authorization_info);
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
