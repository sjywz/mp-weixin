<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'resource';

    public static $type = [
        1 => '图片',
        2 => '视频',
        3 => '语音',
        4 => '文件',
    ];
}
