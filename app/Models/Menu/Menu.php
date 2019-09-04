<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 * @package App\Models\Menu
 * @Author YiYuan-LIn
 * @Date: 2019/9/3
 * 菜品模型类
 */
class Menu extends Model
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

    /**
     * 主键
     * @var
     */
    protected $primaryKey = 'menu_id';

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/3
     * @param $condition
     * @param $sort
     * @param $limit
     * @param $offset
     * @description 获取菜品列表
     */
    private static function _getMenu($condition = [], $sort = [], $limit = '', $offset = '')
    {
        $query = static::query();





    }
}
