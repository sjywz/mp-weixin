<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MpUser extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'mp_users';

    protected $casts = [
        'tagid_list' => 'array',
    ];

    public function mp(): BelongsTo
    {
        return $this->belongsTo(Mp::class, 'appid', 'appid');
    }
}
