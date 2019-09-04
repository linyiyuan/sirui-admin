<?php

namespace App\Models\Menu;

use App\Models\BaseModels;

/**
 * Class Menu
 * @package App\Models\Menu
 * @Author YiYuan-LIn
 * @Date: 2019/9/3
 * 菜品模型类
 */
class Menu extends BaseModels
{
    /**
     * 数据库表
     * @var
     */
    protected $table = 'menu';

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
     * @Date: 2019/9/4
     * @enumeration:
     * @param array $condition
     * @param array $sort
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed
     */
    public static function getMenu($condition = [], $sort = [])
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
    public static function setMenu($data, $id = '')
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
     * @Date: 2019/9/4
     * @param $restaurant_id
     * @param $pageInfo
     * @return mixed
     * @description 根据餐厅获取相对应菜单
     */
    public static function getMenuByRestaurant($restaurant_id, $pageInfo)
    {
        if (empty($restaurant_id)) return [];

        $condition['a.restaurant_id'] = $restaurant_id;
        $query = static::getMenu($condition);

        $query = $query->from('menu as a')
                        ->select('a.menu_id', 'a.menu_name', 'a.menu_amount', 'a.menu_status', 'a.created_at', 'a.updated_at',
                            'a.restaurant_id', 'b.restaurant_name', 'a.menu_type_id', 'c.menu_type_name')
                        ->leftJoin('restaurant as b', 'a.restaurant_id', 'b.restaurant_id')
                        ->leftJoin('menu_type as c', 'a.menu_type_id', 'c.menu_type_id');

        $total = $query->count();
        $query = static::_pagingCondition($query, $pageInfo['cur_page'], $pageInfo['page_size']);

        return [
            'list' => $query->get(),
            'total' => $total
        ];
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/4
     * @param $restaurant_id
     * @return mixed
     * @description 根据餐厅获取相对应分组菜单
     */
    public static function getMenuGroupByMenuType($restaurant_id)
    {
        if (empty($restaurant_id)) return [];

        $condition['a.restaurant_id'] = $restaurant_id;
        $query = static::getMenu($condition);

        $query = $query->from('menu as a')
                        ->select('a.menu_id', 'a.menu_name', 'a.menu_amount', 'a.menu_status', 'a.created_at', 'a.updated_at',
                            'a.restaurant_id', 'b.restaurant_name', 'a.menu_type_id', 'c.menu_type_name')
                        ->leftJoin('restaurant as b', 'a.restaurant_id', 'b.restaurant_id')
                        ->leftJoin('menu_type as c', 'a.menu_type_id', 'c.menu_type_id');

        $menuList = $query->get()->toArray();
        if (empty($menuList)) return [];

        $menuTypeList = [];
        $menuType = array_column($menuList, 'menu_type_name', 'menu_type_id');

        foreach ($menuType  as $key => $value) {
            $menuTypeList[$key] =  [
                'label' => $value,
                'value' => $key,
                'menuList' => []
            ];
        }
        foreach ($menuList as $key => $value) {
            array_push($menuTypeList[$value['menu_type_id']]['menuList'], $value);
        }

        return [
            'list' => array_values($menuTypeList),
        ];
    }
}
