<?php
namespace App\Services;

use App\Models\Platform;
use App\Models\Mp;
use EasyWeChat\OpenPlatform\Application;
use EasyWeChat\OfficialAccount\Application as OApplication;

class WeixinService
{
    public function getApp($id,$solo = false)
    {
        if($solo){
            $plat = Mp::where('id',$id)
                ->orWhere('appid',$id)
                ->first();
            if(empty($plat)){
                throw new \Exception('平台信息不存在');
            }
            $config = [
                'app_id'  => $plat->appid,
                'secret'  => $plat->app_secret,
                'token'   => $plat->verify_token,
                'aes_key' => $plat->msg_key,
                'http' => [
                    'throw'   => true,
                    'timeout' => 5.0,
                    'retry'   => true,
                ],
            ];

            $app = new OApplication($config);
            return $app;
        }else{
            $plat = Platform::where('id',$id)->orWhere('appid',$id)->first();
            if(empty($plat)){
                throw new \Exception('平台信息不存在');
            }
            $config = [
                'app_id'  => $plat->appid,
                'secret'  => $plat->app_secret,
                'token'   => $plat->verify_token,
                'aes_key' => $plat->msg_key,
                'http' => [
                    'throw'   => true,
                    'timeout' => 5.0,
                    'retry'   => true,
                ],
            ];
            $app = new Application($config);
            return $app;
        }
    }
}
