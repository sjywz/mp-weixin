<?php

namespace App\Jobs;

use App\Models\Mp;
use App\Models\MpMessage;
use App\Services\Resource2Media;
use App\Services\WeixinService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WxReply implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $replyData;
    protected $mpAndUserInfo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($replyList, $mpAndUserInfo)
    {
        $this->replyData = $replyList;
        $this->mpAndUserInfo = $mpAndUserInfo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->replyData as $v){
            $this->_sendMsg($v);
        }
    }

    private function _getClient()
    {
        $appid = $this->mpAndUserInfo['appid'];
        $platAappid = $this->mpAndUserInfo['plat_aappid'];

        $weixin = new WeixinService();
        if($platAappid){
            $mp = Mp::where('appid',$appid)
                ->where('plat_appid',$platAappid)
                ->first();
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

    private function _sendMsg($reply)
    {
        $appid = $this->mpAndUserInfo['appid'];
        $platAappid = $this->mpAndUserInfo['plat_aappid'];
        $replyMsgId = $this->mpAndUserInfo['reply_msgid'];
        $openid = $this->mpAndUserInfo['openid'];

        $client = $this->_getClient();

        $type = $reply['MsgType'];
        $data = [
            'touser' => $openid,
            'msgtype' => $type,
        ];
        if($type == 'text'){
            $data[$type] = ['content'=>$reply['Content']];
        }else{
            if(isset($reply['MediaId'])){
                $data[$type] = ['media_id'=>$reply['MediaId']];
            }else{
                try{
                    $image = $reply['path'];
                    $resource2Media = new Resource2Media();
                    $media = $resource2Media->setClient($client)
                        ->setType($type)
                        ->upload($image, true);
                    if($media){
                        $data[$type] = ['media_id'=>$media];
                        DB::table('material')->insertOrIgnore([
                            'url' => $image,
                            'type' => $type,
                            'appid' => $appid,
                            'media_id' => $media,
                            'is_temp' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }catch(\Exception $e){
                    Log::error('消息发送失败,图片转素材失败',[
                        'reply'=>$reply,
                        'err'=>$e->getMessage()
                    ]);
                }
            }
        }

        if($data && isset($data[$type]) && $data[$type]){
            $response = $client->postJson(
                '/cgi-bin/message/custom/send', $data
            );
            $result = $response->getContent();
            MpMessage::create([
                'to' => $openid,
                'from' => $appid,
                'type' => $type,
                'msgid' => uniqid(),
                'appid' => $appid,
                'content' => json_encode($data[$type]),
                'plat_appid' => $platAappid,
                'reply_msgid' => $replyMsgId,
                'create_time' => time(),
                'rest' => $result
            ]);
        }
    }
}
