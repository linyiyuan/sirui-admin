<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MenuConfig
 * @package App\Models\Menu
 * @Author YiYuan-LIn
 * @Date: 2019/9/6
 * 下单配置模型类
 */
class MenuConfig extends Model
{
    /**
     * 数据库表
     * @var
     */
    protected $table = 'menu_config';

    /**
     * 时间戳自动更新开关
     * @var
     */
    public $timestamps = false;

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/6
     * @enumeration:
     * @return array
     * @description 获取配置列表
     */
    public static function getMenuConfig()
    {
        $query = static::query();
        return $query->first()->toArray();
    }
}
