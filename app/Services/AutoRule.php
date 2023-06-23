<?php
namespace App\Services;

use App\Models\MpMessage;
use Illuminate\Support\Facades\DB;

class AutoRule
{
    public static function buildContext($appid, $context)
    {
        $imageIds = array_unique(array_reduce(array_column($context,'image'),function($p,$c){
            return array_merge($p,$c);
        },[]));

        $reourceList = [];
        $materialListByUrl = [];
        if($imageIds){
            $reourceList = DB::table('resource')
                ->whereIn('id',$imageIds)
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
                }else if($type === 'image'){
                    $filterList = array_filter($reourceList,function($v) use ($content){
                        return in_array($v->id,$content);
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
}
