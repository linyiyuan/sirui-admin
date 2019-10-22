<?php
namespace App\Http\Services\Menu;

use App\Http\Services\BaseService;
use App\Models\Menu\Menu;
use App\Models\Menu\MenuRecord;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class MenuService
 * @package App\Http\Services
 * @Author YiYuan-LIn
 * @Date: 2019/9/5
 * 下单操作服务类
 */
class MenuService extends BaseService
{

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/6
     * @enumeration:
     * @param $data
     * @return bool
     * @throws \Exception
     * @description 用户点餐操作
     */
    public function orderMenu($data)
    {
        if (empty($data)) return false;

        //获取当前用户信息
        $user = JWTAuth::parseToken()->toUser();
        $uid = $user->id;

        //获取下单商品信息
        $amount = $data['amount'];
        $addDate = $data['addDate'];
        $menuInfo = Menu::getMenuById($data['menu_id']);

        //检查金额
        if(!$this->checkoutMenuAmount($menuInfo, $amount)) $this->throwExp(400, '菜品金额与下单金额核实不正确');

        //获取订单信息
        $menuRecord = MenuRecord::getMenuRecordByType($addDate, $data['type']);

        $data['uid'] = $uid;
        $data['addDate'] = $addDate;
        $data['status'] = 1;

        if (empty($menuRecord)) {
            $result = MenuRecord::setMenuRecord($data);
        }else{
            $result = MenuRecord::setMenuRecord($data, $menuRecord['id']);
        }

        return $result;
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/6
     * @enumeration:
     * @param $menuInfo
     * @param $amount
     * @return bool
     * @description 检查下单金额
     */
    public function checkoutMenuAmount($menuInfo, $amount)
    {
        if (empty($menuInfo)) return false;

        $menuRealAmount = array_column($menuInfo, 'menu_amount');
        $menuRealAmount = array_sum($menuRealAmount);

        if ($amount != $menuRealAmount) return false;

        return true;
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/6
     * @enumeration:
     * @param $uid
     * @return array
     * @description 根据用户ID获取相对应菜单记录
     */
    public function getMenuByUid($uid)
    {
        if (empty($uid)) return [];

        $type = [
            '1' => '午餐',
            '2' => '晚餐'
        ];

        $list = [];
        $menuRecordList = MenuRecord::getMenuRecordByUid($uid);

        foreach ($menuRecordList as $menuRecord) {
            $menuList = array_column($menuRecord->menus->toArray(), 'menu_name');

            if (array_key_exists($menuRecord->type, $type))  $list[$menuRecord->addDate][$type[$menuRecord->type]] = join('+', $menuList);
        }
        return $list;
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/6
     * @enumeration:
     * @param $data
     * @return array
     * @description 根据点餐时间点以及点餐日期获取相应点餐记录
     */
    public function getMenuRecordByType($data)
    {
        if (empty($data)) return [];

        $list = [];
        $menuRecordList = MenuRecord::getMenuRecordByType($data['addDate'], $data['type']);
        if (empty($menuRecordList)) return [];

        $list['amount'] = $menuRecordList->amount;
        $list['menu'] = array_column($menuRecordList->menus->toArray(), 'menu_id');
        $list['menuSelected'] = array_column($menuRecordList->menus->toArray(), 'menu_name', 'menu_id');

        return $list;
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/10/15
     * @enumeration:
     * @param $data
     * @return array
     * @description 取消点餐
     */
    public function removeMenuOrder($data)
    {
        if (empty($data)) return [];

        $menuRecordQuery = MenuRecord::query();

        $menuRecordQuery->where('add_date', $data['addDate']);
        $menuRecordQuery->where('type', $data['type']);

        $menuRecordQuery->delete();
    }
}