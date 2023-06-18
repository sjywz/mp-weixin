<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class MpReply extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'mp_reply';

    public static $type = [
        0 => '文本',
        1 => '图片',
        2 => '图文',
        3 => '语音',
        4 => '视频',
        5 => '音乐',
        6 => '菜单',
    ];
}
