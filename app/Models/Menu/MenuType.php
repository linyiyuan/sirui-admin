<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MenuType
 * @package App\Models\Menu
 * @Author YiYuan-LIn
 * @Date: 2019/9/3
 * 菜品分类表
 */
class MenuType extends Model
{
    /**
     * 数据库表
     * @var
     */
    protected $table = 'menu_type';

    /**
     * 时间戳自动更新开关
     * @var
     */
    public $timestamps = true;

    protected $primaryKey = 'menu_type_id';
}
