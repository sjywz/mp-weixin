<?php
namespace App\Services;

use App\Models\MpMessage;
use Illuminate\Support\Facades\DB;

class AutoRule
{
    public static function buildContext($appid, $context)
    {
        $resourceIds = array_reduce(array_filter( array_map(function($v){
            if($v['reply_type'] != 'text'){
                return $v[$v['reply_type']];
            }
            return null;
        },$context)),function($p, $c){
            return array_merge($p,explode(',',$c));
        },[]);

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

        $replyList = array_map(function($v) use ($reourceList, $materialListByUrl){
            $type = $v['reply_type'];
            $content = $v[$type];
            if($content){
                if($type === 'text'){
                    return [
                        'MsgType' => $type,
                        'Content' => $content,
                    ];
                }else if($type === 'image' || $type == 'voice'){
                    $filterList = array_filter($reourceList,function($v) use ($content){
                        return in_array($v->id,explode(',',$content));
                    });
                    if($filterList){
                        $selected = self::randImg($filterList);
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
