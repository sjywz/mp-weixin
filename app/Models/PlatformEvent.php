<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class PlatformEvent extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'platform_event';

    protected $fillable = [
        'appid',
        'create_time',
        'info_type',
        'rest',
    ];
}
