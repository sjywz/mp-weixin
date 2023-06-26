<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutoReply extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'auto_reply';

    protected $casts = [
        'context' => 'array',
    ];

    public static $type = [
        0 => '关键词',
        1 => '用户关注',
        2 => '事件',
    ];

    public static $replyType = [
        'text' => '文本',
        'image' => '图片',
        'voice' => '语音',
    ];

    public function mp(): BelongsTo
    {
        return $this->belongsTo(Mp::class, 'appid', 'appid');
    }
}
