<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class AutoReply extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'auto_reply';

    public static $type = [
        0 => '关键词',
        1 => '事件',
    ];
}
