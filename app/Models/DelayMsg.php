<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DelayMsg extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'delay_msg';

    protected $casts = [
        'content' => 'array',
    ];

    public function mp(): BelongsTo
    {
        return $this->belongsTo(Mp::class, 'appid', 'appid');
    }
}
