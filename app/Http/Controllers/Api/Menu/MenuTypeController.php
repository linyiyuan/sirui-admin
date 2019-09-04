<?php

namespace App\Http\Controllers\Api\Menu;

use App\Http\Controllers\Api\CommonController;
use App\Models\Menu\MenuType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

/**
 * Class MenuTypeController
 * @package App\Http\Controllers\Api\Menu
 * @Author YiYuan-LIn
 * @Date: 2019/9/2
 * 菜品分类
 */
class MenuTypeController extends CommonController
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
                    $result = $this->getMenuTypeSearchList();
                    break;
                default :
                    $result = $this->getMenuTypeList();
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
    private function getMenuTypeList()
    {
        $query = MenuType::query();

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
    private function getMenuTypeSearchList()
    {
        $query = MenuType::query();

        $searchList = $query->orderBy('menu_type_id', 'desc')
            ->pluck('menu_type_name', 'menu_type_id');

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
                'menu_type_name' => $postData['menu_type_name'] ?? '',
            ];

            //配置验证
            $rules = [
                'menu_type_name'            => 'required',
            ];
            $message = [
                'menu_type_name.required'  => '菜品分类名 必填',
            ];

            $this->verifyParams($data, $rules, $message);

            $permission = new MenuType();
            $permission->menu_type_name     = $postData['menu_type_name'];
            $permission->menu_type_status   = $postData['menu_type_status'] ?? 1;

            if (!$permission->save()) $this->throwExp(400, '添加菜品分类失败');

            return handleResult(200, '添加菜品分类成功');
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
                'menu_type_name' => $postData['menu_type_name'] ?? '',
                'id' => $id,
            ];

            //配置验证
            $rules = [
                'menu_type_name' => 'required',
                'id' => 'required|integer',
            ];
            $message = [
                'menu_type_name.required'  => '菜品分类名 必填',
                'id.required'  => '菜品分类名 必填',
                'id.integer'  => 'ID 为非法参数'
            ];

            $this->verifyParams($data, $rules, $message);

            $menuType = MenuType::find($id);
            if (empty($menuType)) $this->throwExp(400, '查询不打该分类信息');
            $menuType->menu_type_name     = $postData['menu_type_name'];
            $menuType->menu_type_status   = $postData['menu_type_status'] ?? 1;

            if (!$menuType->save()) $this->throwExp(400, '修改菜品分类失败');

            return handleResult(200, '修改菜品分类成功');
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

            if (!MenuType::destroy($id)) $this->throwExp(400, '删除菜品分类失败');

            return handleResult(200, '删除成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }
}
