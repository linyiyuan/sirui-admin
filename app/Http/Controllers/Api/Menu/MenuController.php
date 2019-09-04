<?php

namespace App\Http\Controllers\Api\Menu;

use App\Http\Controllers\Api\CommonController;
use App\Models\Menu\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * Class MenuController
 * @package App\Http\Controllers\Api\Menu
 * @Author YiYuan-LIn
 * @Date: 2019/9/3
 * 菜品控制器
 */
class MenuController extends CommonController
{
    /**
     * 请求参数
     * @var array
     */
    private $params;

    /**
     * MenuController constructor.
     */
    public function __construct()
    {
        $this->params = Input::all();
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/4
     * @enumeration:
     * @return \Illuminate\Http\JsonResponse
     * @description 获取菜单列表
     */
    public function index()
    {
        try {
            $type = $this->params['type'] ?? '';

            switch ($type) {
                case 'groupByMenuType':
                    $result = $this->getMenuListGroupByMenuType();
                    break;
                default :
                    $result = $this->getMenuByRestaurant();
            }

            return handleResult(200, $result);
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/4
     * @enumeration:
     * @description 根据餐厅ID获取对应菜单分页数据
     */
    protected function getMenuByRestaurant()
    {
        $menuList = [];
        $pageInfo['cur_page']   = $this->params['cur_page'] ?? 1;
        $pageInfo['page_size']  = $this->params['page_size'] ?? 15;

        //获取餐馆ID
        $restaurant_id = $this->params['restaurant_id'] ?? 0;
        $menuList = Menu::getMenuByRestaurant($restaurant_id, $pageInfo);

        return $menuList;
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/4
     * @enumeration:
     * @return array|mixed
     * @description 根据餐厅ID获取按照类别分组菜品数据
     */
    protected function getMenuListGroupByMenuType()
    {
        $menuList = [];

        //获取餐馆ID
        $restaurant_id = $this->params['restaurant_id'] ?? 0;
        $menuList = Menu::getMenuGroupByMenuType($restaurant_id);

        return $menuList;
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/4
     * @enumeration:
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @description 添加记录
     */
    public function store(Request $request)
    {
        try {
            $postData = $request->postData;

            $data = [
                'menu_name' => $postData['menu_name'] ?? '',
                'menu_status' => $postData['menu_status'] ?? 1,
                'menu_amount' => $postData['menu_amount'] ?? '',
                'menu_type_id' => $postData['menu_type_id'] ?? '',
                'restaurant_id' => $postData['restaurant_id'] ?? '',
            ];

            //配置验证
            $rules = [
                'menu_name'  => 'required',
                'menu_amount' => 'required',
                'menu_type_id' => 'required',
                'restaurant_id' => 'required',
            ];
            $message = [
                'menu_name.required' => '菜品名 必填',
                'menu_amount.required' => '菜品金额 必填',
                'menu_type_id.required' => '菜品分类名 必选择',
                'restaurant_id.required' => '餐馆 必选泽',
            ];

            $this->verifyParams($data, $rules, $message);

            if (!Menu::setMenu($data)) $this->throwExp(400, '添加菜品失败');
            return handleResult(200, '添加菜品成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }


    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/4
     * @enumeration:
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @description 修改记录
     */
    public function update(Request $request, $id)
    {
        try {
            $postData = $request->postData;

            $data = [
                'menu_id' => $id,
                'menu_name' => $postData['menu_name'] ?? '',
                'menu_status' => $postData['menu_status'] ?? 1,
                'menu_amount' => $postData['menu_amount'] ?? '',
                'menu_type_id' => $postData['menu_type_id'] ?? '',
                'restaurant_id' => $postData['restaurant_id'] ?? '',
            ];

            //配置验证
            $rules = [
                'menu_name'  => 'required',
                'menu_id'  => 'required',
                'menu_amount' => 'required',
                'menu_type_id' => 'required',
                'restaurant_id' => 'required',
            ];
            $message = [
                'menu_id.required' => 'id 必填',
                'menu_name.required' => '菜品名 必填',
                'menu_amount.required' => '菜品金额 必填',
                'menu_type_id.required' => '菜品分类名 必选择',
                'restaurant_id.required' => '餐馆 必选泽',
            ];

            $this->verifyParams($data, $rules, $message);

            if (!Menu::setMenu($data, $id)) $this->throwExp(400, '修改菜品失败');
            return handleResult(200, '修改菜品成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/3
     * @enumeration:
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @description 删除菜品
     */
    public function destroy($id)
    {
        try {
            if (!intval($id)) $this->throwExp(400, '删除失败，参数错误');

            if (!Menu::destroy($id)) $this->throwExp(400, '删除菜品失败');

            return handleResult(200, '删除成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }
}
