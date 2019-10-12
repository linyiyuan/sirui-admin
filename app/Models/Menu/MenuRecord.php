<?php

namespace App\Models\Menu;

use App\Models\BaseModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

        $menu = $data['menu_id'];
        unset($data['menu_id']);

        if (intval($id) > 0) {
            static::query()->where('id', $id)
                            ->update($data);

            DB::table('menu_record_has_menu')->where('menu_record_id', $id)->delete();

            foreach ($menu as $key) {
                DB::table('menu_record_has_menu')->insert([
                    'menu_record_id' => $id,
                    'menu_id' => $key,
                ]);
            }

        }else{
            $insertId = static::query()->where('id', $id)
                                ->insertGetId($data);

            foreach ($menu as $key) {
                DB::table('menu_record_has_menu')->insert([
                    'menu_record_id' => $insertId,
                    'menu_id' => $key,
                ]);
            }

        }

        return true;
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/6
     * @enumeration:
     * @param $addDate
     * @param $type
     * @return array
     * @description 根据日期以及时间点获取下单记录
     */
    public static function getMenuRecordByType($addDate, $type)
    {
        if (empty($addDate)) return [];
        if (empty($type)) return [];

        $condition['addDate'] = $addDate;
        $condition['type'] = $type;

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
