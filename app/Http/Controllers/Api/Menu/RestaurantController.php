<?php

namespace App\Http\Controllers\Api\Menu;

use App\Http\Controllers\Api\CommonController;
use App\Models\Menu\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * Class MenuTypeController
 * @package App\Http\Controllers\Api\Menu
 * @Author YiYuan-LIn
 * @Date: 2019/9/2
 * 菜品分类
 */
class RestaurantController extends CommonController
{
    /**
     * 请求参数
     * @var array
     */
    private $params;

    /**
     * MenuTypeController constructor.
     */
    public function __construct()
    {
        $this->params = Input::all();
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/3
     * @enumeration:
     * @return \Illuminate\Http\JsonResponse
     * @description 菜品分类列表
     */
    public function index()
    {
        try {
            $type = $this->params['type'] ?? '';

            switch ($type) {
                case 'search':
                    $result = $this->getRestaurantSearchList();
                    break;
                default :
                    $result = $this->getRestaurantList();
            }

            return handleResult(200, $result);
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/3
     * @enumeration:
     * @return array
     * @description 获取餐馆分页列表
     */
    private function getRestaurantList()
    {
        $query = Restaurant::query();

        $total = $query->count();

        $query = $this->pagingCondition($query, $this->params);

        $menuTypeList = $query->get()->toArray();

        return [
            'total' => $total,
            'list' => $menuTypeList
        ];
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/3
     * @enumeration:
     * @return array
     * @description 获取餐馆查询列表
     */
    private function getRestaurantSearchList()
    {
        $query = Restaurant::query();

        $searchList = $query->orderBy('restaurant_id', 'desc')
                            ->pluck('restaurant_name', 'restaurant_id');

        $searchList = $this->combineSearch($searchList);

        return [
            'list' => $searchList
        ];
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/3
     * @enumeration:
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @description 添加菜品分类
     */
    public function store(Request $request)
    {
        try {
            $postData = $request->postData;

            $data = [
                'restaurant_name' => $postData['restaurant_name'] ?? '',
            ];

            //配置验证
            $rules = [
                'restaurant_name' => 'required',
            ];
            $message = [
                'restaurant_name.required'  => '餐馆名 必填',
            ];

            $this->verifyParams($data, $rules, $message);

            $permission = new Restaurant();
            $permission->restaurant_name     = $postData['restaurant_name'];
            $permission->restaurant_status   = $postData['restaurant_status'] ?? 1;

            if (!$permission->save()) $this->throwExp(400, '添加餐馆失败');

            return handleResult(200, '添加餐馆成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/3
     * @enumeration:
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @description 修改菜品分类
     */
    public function update(Request $request, $id)
    {
        try {
            $postData = $request->postData;

            $data = [
                'restaurant_name' => $postData['restaurant_name'] ?? '',
                'id' => $id,
            ];

            //配置验证
            $rules = [
                'restaurant_name' => 'required',
                'id' => 'required|integer',
            ];
            $message = [
                'menu_type_name.required'  => '菜品分类名 必填',
                'id.required'  => '菜品分类名 必填',
                'id.integer'  => 'ID 为非法参数'
            ];

            $this->verifyParams($data, $rules, $message);

            $menuType = Restaurant::find($id);
            if (empty($menuType)) $this->throwExp(400, '查询不到该餐馆信息');
            $menuType->restaurant_name     = $postData['restaurant_name'];
            $menuType->restaurant_status   = $postData['restaurant_status'] ?? 1;

            if (!$menuType->save()) $this->throwExp(400, '修改餐馆失败');

            return handleResult(200, '修改餐馆成功');
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
     * @description 删除菜品分类
     */
    public function destroy($id)
    {
        try {
            if (!intval($id)) $this->throwExp(400, '删除失败，参数错误');

            if (!Restaurant::destroy($id)) $this->throwExp(400, '删除餐馆失败');

            return handleResult(200, '删除餐馆成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }
}
