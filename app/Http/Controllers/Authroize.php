<?php
namespace App\Http\Controllers;

use App\Models\Mp;
use App\Services\WeixinService;
use Illuminate\Support\Facades\Config;

class Authroize extends Controller
{
    public function index($appid)
    {
        try{
            $plat = new WeixinService();
            $app = $plat->getApp($appid);
            $base = Config::get('app.url');
            $callback = $base.'/authroize/call/'.$appid;
            $options = [
                'auth_type' => 1,
            ];
            $url = $app->createPreAuthorizationUrl($callback,$options);
            $url = str_replace('cgi-bin/componentloginpage?','safe/bindcomponent?action=bindcomponent&no_scan=1&',$url);
            return response("<script>window.location.href='$url';</script>")->header('Content-Type','text/html');
        }catch(\Exception $e){
            exit($e->getMessage());
        }
    }

    public function call($appid)
    {
        try{
            $plat = new WeixinService();
            $app = $plat->getApp($appid);
            $account = $app->getAccount();
            $pAppid = $account->getAppId();
            $server = $app->getServer();
            $auth_code = request()->get('auth_code');
            if($auth_code){
                $authorization = $app->getAuthorization($auth_code);
                $aAppid = $authorization->getAppId();
                $accessToken = $authorization->getAccessToken();
                $refreshToken = $authorization->getRefreshToken();

                $authorizationInfo = $authorization->authorization_info;
                $expires_in = $authorizationInfo['expires_in'];
                $func_info  = $authorizationInfo['func_info'];

                $mpinfo = Mp::where('appid',$aAppid)->where('plat_appid',$pAppid)->first();

                if($mpinfo){
                    $data = [
                        'refresh_token' => $refreshToken,
                        'func_info' => json_encode($func_info)
                    ];
                    Mp::where('id',$mpinfo->id)->update($data);
                }else{
                    $data = [
                        'name' => $aAppid,
                        'appid' => $aAppid,
                        'refresh_token' => $refreshToken,
                        'plat_appid' => $pAppid,
                        'func_info' => json_encode($func_info)
                    ];
                    Mp::create($data);
                }
                echo sprintf('APPID：%s，授权成功',$aAppid);
            }else{
                echo '预授权码不存在，请重新操作';
            }
        }catch(\Exception $e){
            $error = $e->getMessage();
            echo '授权失败:'.$error;
        }
    }
}
