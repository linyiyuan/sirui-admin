<?php

namespace App\Models\Game;

use App\Models\BaseModels;
use Illuminate\Database\Eloquent\Model;

class GameOrderRank extends BaseModels
{
    /**
     * 数据库表
     * @var
     */
    protected $table = 'game_order_rank';

    /**
     * 时间戳自动更新开关
     * @var
     */
    public $timestamps = false;

    public static function getGameOrderRankList($pageInfo)
    {
        $query = static::query();
        $query = $query->from('game_order_rank as a');
        $query = $query->leftJoin('game_user as b', 'a.account', 'b.account');
        $query = $query->select('a.*', 'b.phone_num', 'b.game_type');
        $query = $query->orderBy('amount', 'desc');
        $total = $query->count();

        $query = static::_pagingCondition($query, $pageInfo['cur_page'], $pageInfo['page_size']);

        return [
            'list' => $query->get(),
            'total' => $total
        ];
    }
}
