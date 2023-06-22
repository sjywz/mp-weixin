<?php
namespace App\Services;

use EasyWeChat\Kernel\HttpClient\AccessTokenAwareClient;
use Illuminate\Support\Facades\DB;

class Resource2Media
{
    public static function upload(AccessTokenAwareClient $client, $file, $temp = false)
    {
        if($file){
            list($type, $path) = explode(':',$file);
            $file = storage_path('app/public/'.$path);
            $api = '/cgi-bin/material/add_material';
            if($temp){
                $api = '/cgi-bin/media/upload';
            }
            try{
                $media = $client->withFile($file, 'media')->post($api.'?type='.$type);
                $result = json_decode($media->getContent(),true);
                if(isset($result['media_id'])){
                    self::saveMedia($result['media_id'], $path, $type);
                    return $result['media_id'];
                }
            }catch(\Exception $e){

            }
        }
    }

    public static function saveMedia($media,$image,$type)
    {
        DB::table('material')->insertOrIgnore(
            [
                'media_id' => $media,
                'name' => $image,
                'url' => $image,
                'is_temp' => 1,
                'type' => $type,
                'appid' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        );
    }
}
