<?php
namespace App\Services;

use App\Models\MpMessage;

class ReplyService
{
    public static function handle($appid,$message,$platAappid = null)
    {
        $openid = $message->FromUserName;
        $to = $message->ToUserName;
        $msgId = $message->MsgId;

        $type = 'text';
        $content = '你好:'.$appid.'===>'.$platAappid;
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
            'content' => $content,
            'reply_msgid' => $msgId,
            'rest' => json_encode($reply)
        ]);
        return $reply;
    }
}
