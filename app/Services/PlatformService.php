<?php
namespace App\Services;

use App\Models\Platform;
use EasyWeChat\OpenPlatform\Application;

class PlatformService
{
    public function getApp($id)
    {
        $plat = Platform::find($id);
        $config = [
            'app_id'  => $plat->appid, // 开放平台账号的 appid
            'secret'  => $plat->app_secret,
            'token'   => $plat->verify_token,
            'aes_key' => $plat->msg_key,
            'http' => [
                'throw'  => true,
                'timeout' => 5.0,
                'retry' => true,
            ],
        ];
        $app = new Application($config);
        return $app;
    }
}
