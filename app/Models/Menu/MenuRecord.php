<?php

namespace App\Models\Menu;

use App\Models\BaseModels;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MenuRecord
 * @package App\Models\Menu
 * @Author YiYuan-LIn
 * @Date: 2019/9/4
 * 点餐记录
 */
class MenuRecord extends BaseModels
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

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/6
     * @enumeration:
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @description 获取订单对应的商品详情
     */
    public function menus()
    {
        return $this->belongsToMany('App\Models\Menu\Menu', 'menu_record_has_menu', 'menu_record_id', 'menu_id');
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/4
     * @enumeration:
     * @param array $condition
     * @param array $sort
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed
     */
    public static function getMenuRecord($condition = [], $sort = [])
    {
        $query = static::query();

        //设置条件以及排序
        $query = static::_setCondition($query, $condition);
        $query = static::_setSort($query, $sort);

        return $query;
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/4
     * @enumeration:
     * @param $data
     * @param string $id
     * @return bool
     */
    public static function setMenuRecord($data, $id = '')
    {
        if (empty($data)) return false;

        if (intval($id) > 0) {
            $menuObj = static::find($id);
            if (empty($menuObj)) return false;
        }else{
            $menuObj = new static();
        }

        foreach ($data as $key => $value) {
            $menuObj->$key = $value;
        }

        if (!$menuObj->save()) return false;

        return true;
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/6
     * @enumeration:
     * @param $addDate
     * @return array
     * @description 根据日期获取下单记录
     */
    public static function getMenuRecordByAddDate($addDate)
    {
        if (empty($addDate)) return [];
        $condition['addDate'] = $addDate;

        $query = static::getMenuRecord($condition);
        $menuRecord = $query->first();

        return $menuRecord;
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/6
     * @enumeration:
     * @param $uid
     * @return array
     * @description 根据用户ID获取下单记录
     */
    public static function getMenuRecordByUid($uid)
    {
        if (empty($uid)) return [];
        $condition['uid'] = $uid;

        $query = static::getMenuRecord($condition);
        $menuRecord = $query->get();

        return $menuRecord;
    }
}
