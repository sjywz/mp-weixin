<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformEvent extends Model
{
    public $timestamps = false;

    protected $table = 'platform_event';

    protected $fillable = [
        'appid',
        'create_time',
        'info_type',
        'rest',
    ];
}
