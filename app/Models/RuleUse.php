<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RuleUse extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'rule_use';
}
