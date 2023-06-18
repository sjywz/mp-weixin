<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mp extends Model
{
	use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'mp';

    protected $fillable = [
        'name',
        'icon',
        'appid',
        'app_secret',
        'verify_token',
        'refresh_token',
        'msg_key',
        'type',
        'desc',
        'plat_appid',
        'func_info',
        'origin_id',
        'principal_name',
        'status',
        'account_type'
    ];

    public static $type = [
        0 => '公众号',
        1 => '小程序'
    ];

    public static $accountType = [
        [
            0 => '订阅号',
            1 => '订阅号(老帐号升级)',
            2 => '服务号'
        ],
        [
            0 => '普通小程序',
            2 => '门店小程序',
            3 => '门店小程序',
            4 => '小游戏',
            12 => '试用小程序',
            10 => '小商店',
        ]
    ];

    public static $accountStatus = [
        1 => '正常',
        14 => '已注销',
        16 => '已封禁',
        18 => '已告警',
        19 => '已冻结'
    ];

    public function replys(): BelongsToMany
    {
        $useTable = 'rule_use';
        $relatedModel = AutoReply::class;
        return $this->belongsToMany($relatedModel, $useTable, 'mp_id', 'rule_id');
    }
}
