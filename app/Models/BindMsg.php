<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class BindMsg extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'bind_msg';
    
}
