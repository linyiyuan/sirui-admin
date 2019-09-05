<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MenuRecord
 * @package App\Models\Menu
 * @Author YiYuan-LIn
 * @Date: 2019/9/4
 * 点餐记录
 */
class MenuRecord extends Model
{
    /**
     * 数据库表
     * @var
     */
    protected $table = 'menu_record';

    /**
     * 时间戳自动更新开关
     * @var
     */
    public $timestamps = false;

    /**
     * 设定主键
     * @var
     */
    protected $primaryKey = 'id';
}
