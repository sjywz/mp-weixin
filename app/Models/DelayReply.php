<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class DelayReply extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'delay_reply';
    
}
