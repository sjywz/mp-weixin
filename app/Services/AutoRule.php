<?php
namespace App\Services;

use App\Models\MpMessage;
use Illuminate\Support\Facades\DB;

class AutoRule
{
    private static function _getResource($appid, $resourceIds)
    {
        $reourceList = [];
        $materialListByUrl = [];
        if($resourceIds){
            $reourceList = DB::table('resource')
                ->whereIn('id',$resourceIds)
                ->select('id','name','path','wight')
                ->get()
                ->all();

            if($reourceList){
                $pathArr = array_column($reourceList,'path');
                $materialList = DB::table('material')
                    ->where('appid',$appid)
                    ->whereIn('url',$pathArr)
                    ->select(['id','media_id','url'])
                    ->get();

                if($materialList){
                    $materialListByUrl = $materialList->pluck('media_id','url')->all();
                }
            }
        }

        return [$reourceList, $materialListByUrl];
    }

    public static function delayMsg($appid, $openid, $context, $replyRuleId = 0)
    {
        $type = $context['reply_type'];
        $content = $context[$type];

        if($type === 'text'){
            return [
                'MsgType' => $type,
                'Content' => $content,
            ];
        }

        if($content){
            list($reourceList, $materialListByUrl) = self::_getResource($appid, $content);
            return self::_buildImageOrVoice($type, $content, $reourceList, $materialListByUrl, $appid, $openid, $replyRuleId);
        }
    }

    private static function _buildImageOrVoice($type, $content, $reourceList, $materialListByUrl, $appid, $openid, $replyRuleId)
    {
        $isBind = $v['bind'] ?? 0;
        $imageList = array_filter($reourceList,function($v) use ($content){
            if(is_array($content)){
                return in_array($v->id,$content);
            }
            return in_array($v->id,explode(',',$content));
        });

        if($imageList){
            if($isBind){
                $bindMsg = DB::table('bind_msg')
                    ->where('appid',$appid)
                    ->where('openid',$openid)
                    // ->where('reply_id',$replyRuleId)
                    ->select(['id','source_id'])
                    ->orderBy('id','desc')
                    ->first();
                if($bindMsg){
                    $sourceId = $bindMsg->source_id;
                    $selectedArr = array_filter($imageList,function($v) use ($sourceId){
                        return $v->id == $sourceId;
                    });
                    if($selectedArr){
                        $selected = collect($selectedArr)->first();
                    }
                }
            }

            if(empty($selected)){
                $selected = self::randImg($imageList);
                if($isBind){
                    //保存用户回复
                    DB::table('bind_msg')->insert([
                        'appid' => $appid,
                        'openid' => $openid,
                        'reply_id' => $replyRuleId,
                        'source_id' => $selected->id,
                    ]);
                }
            }

            $path = collect($selected)->get('path');
            if(isset($materialListByUrl[$path]) && $materialListByUrl[$path]){
                $mediaId = $materialListByUrl[$path];
                return [
                    'MsgType' => $type,
                    'MediaId' => $mediaId,
                ];
            }else{
                return [
                    'MsgType' => $type,
                    'path' => $path,
                ];
            }
        }
    }

    public static function buildContext($appid, $openid, $replyRuleId, $context)
    {
        $resourceIds = array_reduce(array_filter( array_map(function($v){
            if($v['reply_type'] != 'text'){
                return $v[$v['reply_type']];
            }
            return null;
        },$context)),function($p, $c){
            return array_merge($p,explode(',',$c));
        },[]);

        list($reourceList, $materialListByUrl) = self::_getResource($appid, $resourceIds);

        $replyList = array_map(function($v) use ($reourceList, $materialListByUrl, $appid, $openid, $replyRuleId){
            $type = $v['reply_type'];
            $content = $v[$type];
            if($content){
                if($type === 'text'){
                    return [
                        'MsgType' => $type,
                        'Content' => $content,
                    ];
                }else if($type === 'image' || $type == 'voice'){
                    return self::_buildImageOrVoice($type, $content, $reourceList, $materialListByUrl, $appid, $openid, $replyRuleId);
                }
            }
            return null;
        },$context);

        return $replyList;
    }

    public static function randImg($data)
    {
        if(count($data) > 1){
            $total_weight = array_sum(array_column($data, 'wight'));
            $rand = mt_rand(1, $total_weight);
            $selected = null;

            foreach ($data as $item) {
                $rand -= $item->wight ?: 1;
                if ($rand <= 0) {
                    $selected = $item;
                    break;
                }
            }

            return $selected;
        }
        return array_pop($data);
    }
}
