<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Mp extends Model
{
	use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'mp';

    public static $type = [
        0 => '公众号',
        1 => '小程序'
    ];
}
