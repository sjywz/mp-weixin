<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MpMessage extends Model
{
    public $timestamps = false;

    protected $table = 'mp_message';

    protected $fillable = [
        'type',
        'msgid',
        'appid',
        'create_time',
        'from',
        'to',
        'event',
        'event_key',
        'content',
        'rest',
        'reply_msgid',
        'plat_appid',
        'sender'
    ];

    protected function createTime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date('Y-m-d H:i:s',$value),
        );
    }

    public function mp(): BelongsTo
    {
        return $this->belongsTo(Mp::class, 'appid', 'appid');
    }
}
