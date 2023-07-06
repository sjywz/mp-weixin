<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DelayReply extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'delay_reply';

    public function msg(): BelongsTo
    {
        return $this->belongsTo(DelayMsg::class, 'msg_id', 'id');
    }
}
