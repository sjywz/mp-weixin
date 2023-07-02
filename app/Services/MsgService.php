<?php
namespace App\Services;

use App\Jobs\MpUserSave;
use App\Models\MpMessage;
use App\Models\PlatformEvent;

class MsgService
{
    public static function falsh($message,$appid,$platAppid = null)
    {
        $msgType = $message->MsgType;
        $msgId = $message->MsgId;
        $createTime = $message->CreateTime;
        $openid = $message->FromUserName;
        $to = $message->ToUserName;
        $event = $message->Event;
        $eventKey = $message->EventKey;

        MpUserSave::dispatch([
            'event' => $event,
            'appid' => $appid,
            'openid' => $openid,
            'plat_appid' => $platAppid,
        ]);

        if($msgType === 'text'){
            $content = $message->Content;
        }else{
            $content = json_encode(array_filter([
                'MediaId' => $message->MediaId,
                'PicUrl' => $message->PicUrl,
                'Format' => $message->Format,
                'Recognition' => $message->Recognition,
                'ThumbMediaId' => $message->ThumbMediaId,
                'Scale' => $message->Scale,
                'Label' => $message->Label,
                'Title' => $message->Title,
                'Description' => $message->Description,
                'Url' => $message->Url,
                'FileKey' => $message->FileKey,
                'FileMd5' => $message->FileMd5,
                'FileTotalLen' => $message->FileTotalLen,
            ]));
        }

        $data = [
            'to' => $appid,
            'type' => $msgType,
            'msgid' => $msgId,
            'appid' => $appid,
            'from' => $openid,
            'event' => $event,
            'event_key' => $eventKey,
            'content' => $content,
            'create_time' => $createTime,
            'plat_appid' => $platAppid,
            'sender' => 0
        ];
        MpMessage::create($data);
    }

    public static function flashPlatEvent($message,$platAppid)
    {
        $infoType = $message->InfoType;
        $appId = $message->AppId;
        $createTime = $message->CreateTime;
        $aAppid = $message->AuthorizerAppid;

        PlatformEvent::create([
            'appid' => $appId,
            'create_time' => $createTime,
            'info_type' => $infoType,
            'plat_appid' => $platAppid,
        ]);
    }
}
