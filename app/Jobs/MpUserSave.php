<?php

namespace App\Jobs;

use App\Models\Mp;
use App\Models\MpUser;
use App\Services\WeixinService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MpUserSave implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $appid = $this->data['appid'];
        $event = $this->data['event'];
        $openid = $this->data['openid'];

        $state = $event === 'unsubscribe'?0:1;

        $mpUser = MpUser::firstWhere('openid', $openid);
        if($mpUser){
            $mpUser->last_time = date('Y-m-d H:i:s');
            if($mpUser->updated_at->getTimestamp() > strtotime('-7 day')){
                if($mpUser->subscribe != $state){
                    $mpUser->subscribe = $state;
                }
                $mpUser->save();
                return;
            }
        }else{
            $mpUser = new MpUser();
            $mpUser->appid = $appid;
            $mpUser->openid = $openid;
            $mpUser->subscribe = $state;
            $mpUser->last_time = date('Y-m-d H:i:s');
        }

        if($state === 0){
            return;
        }

        try{
            $client   = $this->_getClient();
            $response = $client->get('/cgi-bin/user/info',['openid'=>$openid]);
            $result   = $response->getContent();
            $resultArr = json_decode($result, true);
            if(empty($resultArr['errcode'])){
                $mpUser->unionid = $resultArr['unionid'] ?? '';
                $mpUser->remark  = $resultArr['remark'];
                $mpUser->groupid = $resultArr['groupid'];
                $mpUser->language = $resultArr['language'];
                $mpUser->tagid_list = $resultArr['tagid_list'];
                $mpUser->subscribe_time = $resultArr['subscribe_time'];
                $mpUser->subscribe_scene = $resultArr['subscribe_scene'];
                $mpUser->save();
            }else{
                $mpUser->updated_at = now();
                $mpUser->subscribe_time = $event === 'subscribe'?time():0;
                $mpUser->save();
                throw new \Exception($resultArr['errmsg']);
            }
        }catch(\Exception $e){
            Log::error('mp_user_save',[
                'data' => $this->data,
                'err' => $e->getMessage()
            ]);
        }
    }

    private function _getClient()
    {
        $appid = $this->data['appid'];
        $platAappid = $this->data['plat_appid'];

        $weixin = new WeixinService();
        if($platAappid){
            $mp = Mp::where('appid',$appid)->where('plat_appid',$platAappid)->first();
            if($mp){
                $app = $weixin->getApp($platAappid);
                $officialAccount = $app->getMiniAppWithRefreshToken($appid, $mp->refresh_token);
                $client = $officialAccount->getClient();
            }
        }else{
            $app = $weixin->getApp($appid,true);
            $client = $app->getClient();
        }

        return $client;
    }
}
