<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GameOrder extends Model
{
    /**
     * 数据库表
     * @var
     */
    protected $table = 'game_order';

    /**
     * 时间戳自动更新开关
     * @var
     */
    public $timestamps = false;

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/10/18
     * @enumeration:
     * @param $account
     * @return array|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @description 根据用户账号获取用户充值信息
     */
    public static function getOrderInfoByAccount($account)
    {
        if (empty($account)) return [];

        $query = static::query();
        $query->where('account', $account);

        return $query->get();
    }
}
