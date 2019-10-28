<?php

namespace App\Models\Game;

use App\Models\BaseModels;
use Illuminate\Database\Eloquent\Model;

class GameUser extends BaseModels
{
    /**
     * 数据库表
     * @var
     */
    protected $table = 'game_user';

    /**
     * 时间戳自动更新开关
     * @var
     */
    public $timestamps = false;

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/10/18
     * @enumeration:
     * @param $pageInfo
     * @return mixed
     * @description 获取游戏用户列表
     */
    public static function getGameUserList($account, $pageInfo)
    {
        $query = static::query();
        $total = $query->count();

        $query = $query->orderBy('amount', 'desc');
//        $query = static::_pagingCondition($query, $pageInfo['cur_page'], $pageInfo['page_size']);
        if (!empty($account)) $query->where('account', $account);

        return [
            'list' => $query->get(),
            'total' => $total
        ];
    }
}
