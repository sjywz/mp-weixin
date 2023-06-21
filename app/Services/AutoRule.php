<?php
namespace App\Services;

use App\Models\MpMessage;
use Illuminate\Support\Facades\DB;

class AutoRule
{
    public static function buildContext($context)
    {
        $imageIds = array_unique(array_reduce(array_column($context,'image'),function($p,$c){
            return array_merge($p,$c);
        },[]));

        $reourceList = DB::table('resource')
            ->whereIn('id',$imageIds)
            ->select('id','name','path','media_id','wight')
            ->get();

        $replyList = [];
        foreach($context as $v){
            $type = $v['reply_type'];
            if($type === 'text'){
                $replyList[] = [
                    'MsgType' => $type,
                    'Content' => $v[$type],
                ];
            }else if($type === 'image'){
                $imageList = $v[$type];
                $rand = rand(0,count($imageList) - 1);
                $replyList[] = [
                    'MsgType' => $type,
                    'MediaId' => $imageList[$rand],
                    'resource' => $reourceList,
                ];
            }
        }

        return $replyList;
    }
}
