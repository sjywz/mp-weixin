<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AutoReply extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'auto_reply_rule';

    public static $type = [
        0 => '关键词',
        1 => '事件',
    ];

    public function mpId(): BelongsToMany
    {
        $useTable = 'rule_use';
        $relatedModel = Mp::class;
        return $this->belongsToMany($relatedModel, $useTable, 'rule_id', 'mp_id');
    }

    public function replyId(): BelongsToMany
    {
        $useTable = 'rule_reply';
        $relatedModel = MpReply::class;
        return $this->belongsToMany($relatedModel, $useTable, 'rule_id', 'reply_id');
    }
}
