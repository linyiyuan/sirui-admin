<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MenuType
 * @package App\Models\Menu
 * @Author YiYuan-LIn
 * @Date: 2019/9/3
 * 餐馆分类表
 */
class Restaurant extends Model
{
    /**
     * 数据库表
     * @var
     */
    protected $table = 'restaurant';

    /**
     * 时间戳自动更新开关
     * @var
     */
    public $timestamps = true;


    protected $primaryKey = 'restaurant_id';
}