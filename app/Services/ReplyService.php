<?php
namespace App\Services;

use App\Jobs\WxReply;
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

        $replyRule = self::_getReplyRule(
            $appid,
            $msgType,
            $event,
            $eventKey,
            $content
        );

        $replyList = [];
        if($replyRule && $replyRule->context){
            $replyContext = json_decode($replyRule->context,true);
            $replyList = AutoRule::buildContext($appid, $openid, $replyRule->id, $replyContext);

            if($replyList){
                $firstReply = [];
                foreach($replyList as $k => $v){
                    if($k === 0){
                        if($v['MsgType'] === 'text'){
                            $firstReply = $v;
                            unset($replyList[$k]);
                        }else if(isset($v['MediaId'])){
                            $v[ucfirst($v['MsgType'])]['MediaId'] = $v['MediaId'];
                            unset($v['MediaId']);
                            $firstReply = $v;
                            unset($replyList[$k]);
                        }
                    }
                }

                if($replyList){
                    WxReply::dispatch($replyList,[
                        'appid' => $appid,
                        'openid' => $openid,
                        'reply_msgid' => $msgId,
                        'plat_aappid' => $platAappid,
                    ]);
                }

                if($firstReply){
                    MpMessage::create([
                        'to' => $openid,
                        'from' => $appid,
                        'type' => $firstReply['MsgType'],
                        'msgid' => uniqid(),
                        'appid' => $appid,
                        'content' => json_encode($firstReply),
                        'reply_msgid' => $msgId,
                        'create_time' => time(),
                        'plat_appid' => $platAappid,
                    ]);
                    return $firstReply;
                }
            }
        }
        return '';
    }

    public static function getReplyRule($appid, $msgType, $event, $eventKey, $content)
    {
        return self::_getReplyRule(
            $appid,
            $msgType,
            $event,
            $eventKey,
            $content
        );
    }

    private static function _getReplyRule($appid, $msgType, $event, $eventKey, $content)
    {
        $where = [
            ['appid','=',$appid],
            ['status', '=', 1],
        ];

        if($msgType == 'event'){
            if($eventKey){
                $replyRule = DB::table('auto_reply')
                    ->where($where)
                    ->where('event',strtolower(trim($eventKey)))
                    ->select(['id','key','event','context'])
                    ->orderBy('wight','desc')
                    ->first();
                if($replyRule){
                    return $replyRule;
                }
            }
            $replyRule = DB::table('auto_reply')
                ->where($where)
                ->where('event',strtolower(trim($event)))
                ->select(['id','key','event','context'])
                ->orderBy('wight','desc')
                ->first();
            if($replyRule){
                return $replyRule;
            }
        }else{
            if(trim($content)){
                $input = trim($content);
                $replyRule = DB::table('auto_reply')
                    ->where($where)
                    ->where('type',0)
                    ->select(['id','key','context'])
                    ->orderBy('wight','desc')
                    ->get();

                foreach($replyRule as $v){
                    $keyArr = explode(',',$v->key);
                    foreach($keyArr as $_v){
                        if($input === $_v){
                            return $v;
                        }
                        if(self::_keyPreg($_v,$input)){
                            return $v;
                        }
                    }
                }
            }
        }
    }

    private static function _keyPreg($key,$input)
    {
        if($key[0] === '%' && $key[strlen($key) - 1] === '%'){
            $pattern = '/'.str_replace('%', '.*', preg_quote($key, '/')).'/';
        }elseif($key[0] === '%'){
            $pattern = '/^' . preg_quote(substr($key, 1), '/').'/';
        }elseif($key[strlen($key) - 1] === '%'){
            $pattern = '/'.preg_quote(substr($key, 0, -1), '/').'$/';
        }else{
            return false;
        }
        return preg_match($pattern, $input);
    }
}
