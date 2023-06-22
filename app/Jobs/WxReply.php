<?php

namespace App\Jobs;

use App\Models\MpMessage;
use App\Services\Resource2Media;
use App\Services\WeixinService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WxReply implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $replyData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->replyData = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $appid = $this->replyData['appid'];
        $type = $this->replyData['MsgType'];
        $openid = $this->replyData['openid'];
        $platAappid = $this->replyData['plat_aappid'];

        $weixin = new WeixinService();
        $app = $weixin->getApp($platAappid?$platAappid:$appid,empty($platAappid));
        $client = $app->getClient();

        $data = [
            'touser' => $openid,
            'msgtype' => $type,
        ];
        if($type == 'text'){
            $data[$type] = ['content'=>$this->replyData['Content']];
        }else{
            if(strpos($this->replyData['MediaId'],'image:') === 0){
                $media = Resource2Media::upload($client,$this->replyData['MediaId'], true);
                $data[$type] = ['media_id'=>$media];
            }
        }

        if($data){
            $response = $client->postJson('/cgi-bin/message/custom/send', $data);
            $result = $response->getContent();
            $replyMsgId = $this->replyData['reply_msgid'];
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
                'rest' => $result,
                'sender' => 999
            ]);
        }
    }
}
