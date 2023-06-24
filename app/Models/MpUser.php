<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class MpUser extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'mp_users';

    protected $casts = [
        'tagid_list' => 'array',
    ];
}
