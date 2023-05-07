<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class MpMenu extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'mp_menu';
    
}
