<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class MpMessage extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'mp_message';

    protected $fillable = [
        'type',
        'msgid',
        'appid',
        'create_time',
        'from',
        'to',
        'event',
        'rest',
    ];
}
