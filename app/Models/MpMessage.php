<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'rest',
    ];
}
