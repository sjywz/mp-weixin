<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class AutoReply extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'auto_reply';
    
}
