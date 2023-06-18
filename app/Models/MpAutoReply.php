<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class MpAutoReply extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'mp_auto_reply';
    
}
