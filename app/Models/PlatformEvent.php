<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PlatformEvent extends Model
{
    public $timestamps = false;

    protected $table = 'platform_event';

    protected $fillable = [
        'appid',
        'create_time',
        'info_type',
        'plat_appid',
        'rest',
    ];

    protected function createTime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date('Y-m-d H:i:s',$value),
        );
    }
}
