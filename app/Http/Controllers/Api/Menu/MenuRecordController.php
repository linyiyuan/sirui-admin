<?php

namespace App\Http\Controllers\Api\Menu;

use App\Http\Controllers\Api\CommonController;
use App\Http\Services\Menu\MenuService;
use Illuminate\Support\Facades\Input;
use Tymon\JWTAuth\Facades\JWTAuth;

class MenuRecordController extends CommonController
{
    /**
     * 请求参数
     * @var
     */
    protected $params;

    /**
     * MenuRecordController constructor.
     */
    public function __construct()
    {
        $this->params = Input::all();
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/6
     * @enumeration:
     * @return \Illuminate\Http\JsonResponse
     * @description 下单操作
     */
    public function orderMenu()
    {
        try {
            $postData = $this->params['postData'] ?? $this->throwExp(400, '请求参数为空');

            $data = [
                'addDate' => $postData['addDate'] ?? '',
                'menu_id' => $postData['menu_id'] ?? '',
                'amount' => $postData['amount'] ?? 0,
            ];

            $rules = [
                'menu_id' => 'required',
                'amount' => 'required|integer',
                'addDate' => 'required|date',
            ];

            $message = [
                'menu_id.required'  => '请选择菜单',
                'amount.required'  => '金额不允许为空',
                'amount.integer'  => '金额不允许为空',
                'addDate.required'  => '日期不允许为空',
                'addDate.date'  => '日期格式不正确',
            ];

            $this->verifyParams($data, $rules, $message);

            $result = MenuService::getInstance()->orderMenu($data);

            if (!$result) $this->throwExp(400, '下单失败');

            return handleResult(200, '下单成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/6
     * @enumeration:
     * @return \Illuminate\Http\JsonResponse
     * @description 根据Uid获取相应的点餐记录
     */
    public function getMenuRecordListByUid()
    {
        try {
            $uid = $this->params['uid'] ?? JWTAuth::parseToken()->toUser()->id;

            $menuRecordList = MenuService::getInstance()->getMenuByUid($uid);

            return handleResult(200, [
                'list' =>$menuRecordList
            ]);
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

}
