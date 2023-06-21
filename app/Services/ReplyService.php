<?php
namespace App\Services;

use App\Models\MpMessage;
use Illuminate\Support\Facades\DB;

class ReplyService
{
    public static function handle($appid,$message,$platAappid = null)
    {
        $openid = $message->FromUserName;
        $msgId = $message->MsgId;
        $to = $message->ToUserName;

        $msgType = $message->MsgType;
        $content = $message->Content;
        $event = $message->Event;
        $eventKey = $message->EventKey;
        $createTime = $message->CreateTime;

        $where = [
            ['appid','=',$appid],
            ['status', '=', 1],
        ];
        if($event === 'subscribe'){
            $where[] = ['event','=','subscribe'];
        }else if($eventKey){
            $where[] = ['event','=',$eventKey];
        }else{
            $where[] = ['type','=',0];
            $where[] = ['key','like','%'.trim($content).'%'];
        }
        $replyRule = DB::table('auto_reply')
            ->where($where)
            ->select(['id','key','key','event','context'])
            ->orderBy('wight','desc')
            ->first();

        $replyList = [];
        if($replyRule){
            $replyContext = json_decode($replyRule->context,true);
            $replyList = AutoRule::buildContext($replyContext);
        }

        $type = 'text';
        $content = json_encode($replyList);
        $reply = [
            'MsgType' => $type,
            'Content' => $content,
        ];

        MpMessage::create([
            'to' => $openid,
            'from' => $to,
            'type' => $type,
            'msgid' => uniqid(),
            'appid' => $appid,
            'content' => $content,
            'reply_msgid' => $msgId,
            'create_time' => time(),
            'rest' => json_encode($reply),
        ]);
        return $reply;
    }
}
