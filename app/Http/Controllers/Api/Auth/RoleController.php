<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends CommonController
{
    /**
     * 请求参数
     * @var
     */
    protected $params;

    /**
     * RoleController constructor.
     */
    public function __construct()
    {
        $this->params = Input::all();
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/5/7
     * @param Request $request
     * @return mixed
     * @description 角色列表
     */
    public function index(Request $request)
    {
        try {
            $type  = $this->params['type'] ?? '';
            $query = Role::query();

            if ($type == 'tree') {
                $tree = $query->select('name', 'description')->get()->toArray();
                $tree = array_column($tree, 'description', 'name');

                return handleResult(200, $tree);
            }

            $total  = $query->count();
            $query  = $this->pagingCondition($query, $this->params);

            //判断是否有查询条件
            if(!empty($this->params['description'])) $query->where('description', 'like', '%' . $this->params['description'] . '%');
            $data   = $query->get();

            //循环给予每个角色一个权限数组
            foreach ($data as $key => $value) {
                $allowPermissions = $value->getAllPermissions()->toArray();
                $data[$key]['allPermissions'] = $allowPermissions;
            }

            return handleResult(200, [
                'total' => $total,
                'list' => $data,
            ]);

        } catch (\Exception $e) {
            return $this->errorExp($e);
        }


    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/5/7
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @description 添加角色
     */
    public function store(Request $request)
    {
        try {
            $postData = $request->postData;
            $params = [
                'name'          => $postData['name'],
                'description'   => $postData['description']
            ];
            //配置验证
            $rules = [
                'name' => 'required',
            ];
            $message = [
                'name.required' => '[name]缺失',
            ];

            $this->verifyParams($params, $rules, $message);

            if (!Role::create($params)) $this->throwExp(100002, '添加角色失败');
            return handleResult(200, '添加角色成功');

        } catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if (!intval($id)) $this->throwExp('未知id参数');

            $postData = $request->postData;
            $params = [
                'name'          => $postData['name'],
                'description'   => $postData['description']
            ];
            //配置验证
            $rules = [
                'name' => 'required',
            ];
            $message = [
                'name.required' => '[name]缺失',
            ];

            $this->verifyParams($params, $rules, $message);

            if (!Role::where('id', $id)->update($params)) $this->throwExp('修改用户失败');

            //正确返回信息
            return handleResult(200, '修改用户成功');

        } catch (\Exception $e) {
            return handleResult(400, $e->getMessage());
        }

    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/6/3
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @description 删除权限
     */
    public function destroy($id)
    {

    }

    /**
     * @author YiYuan Lin
     * @date 18/12/11
     * @describe 获取所有角色列表
     * @return int
     */
    public function roleList()
    {
        $roleList = Role::select('id', 'name')
                ->orderBy('id' ,'asc')
                ->get()
                ->toArray();

         return $this->successReturn(200, $roleList);

    }
}
