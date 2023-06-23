<?php
namespace App\Services;

use EasyWeChat\Kernel\HttpClient\AccessTokenAwareClient;
use Exception;

class Resource2Media
{
    protected AccessTokenAwareClient $client;
    protected $type;

    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    public function upload($file, $temp = false)
    {
        if($file){
            list($type, $path) = explode(':',$file);
            $file = storage_path('app/public/'.$path);
            if(file_exists($file)){
                $api = '/cgi-bin/material/add_material';
                if($temp){
                    $api = '/cgi-bin/media/upload';
                }
                try{
                    $media = $this->client->withFile($file, 'media')
                        ->post($api.'?type='.$type);
                    $result = json_decode($media->getContent(),true);
                    if(isset($result['media_id'])){
                        return $result['media_id'];
                    }else{
                        throw new Exception(json_encode($result));
                    }
                }catch(\Exception $e){
                    throw $e->getMessage();
                }
            }else{
                throw new Exception($path.'文件不存在');
            }
        }
    }
}
