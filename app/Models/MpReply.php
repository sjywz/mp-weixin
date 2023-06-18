<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class MpReply extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'mp_reply';
    
}
