<?php

namespace App\Http\Controllers\Api\Passport;

use App\Http\Controllers\Api\CommonController;
use App\Models\Passport\BundleIdPid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class BundleIdPidController extends CommonController
{
    /**
     * 请求参数
     * @var
     */
    protected $params;

    public function __construct()
    {
        $this->params = Input::all();
    }

    /**
     * 获取包对应渠道列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            if (empty($this->params['bundle_id'])) $this->throwExp(400, '请选择渠道ID');
            $query = BundleIdPid::query();
            $query = $query->where('bundle_id', $this->params['bundle_id']);

            $total = $query->count();

            $query = $this->pagingCondition($query, $this->params);
            $list = $query->get()->toArray();

            return handleResult(200, [
                'list' => $list,
                'total' => $total
            ]);

        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * 添加渠道
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $postData = $this->params['postData'] ?? $this->throwExp(400, '请求参数为空');

            $data = [
                'bundle_id' => $postData['bundle_id'] ?? '',
                'pid' => $postData['pid'] ?? '',
                'sort' => $postData['sort'] ?? 0
            ];
            $rules = [
                'bundle_id'  => 'required',
                'pid'  => 'required',
            ];
            $message = [
                'bundle_id.required' => 'bundle_id不能为空',
                'pid.required' => 'pid不能为空',
            ];
            $this->verifyParams($data, $rules, $message);

            $bundleIdPid = new BundleIdPid();
            $bundleIdPid->bundle_id = $data['bundle_id'];
            $bundleIdPid->pid = $data['pid'];
            $bundleIdPid->sort = $data['sort'];

            if (!$bundleIdPid->save()) $this->throwExp(400, '添加渠道失败');

            return handleResult(200, '添加渠道成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * 修改渠道
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {

            $data = [
                'id' => $id,
                'bundle_id' => $postData['bundle_id'] ?? '',
                'pid' => $postData['pid'] ?? '',
                'sort' => $postData['sort'] ?? 0
            ];
            $rules = [
                'id'  => 'required',
                'bundle_id'  => 'required',
                'pid'  => 'required',
            ];
            $message = [
                'id.required' => 'id不能为空',
                'bundle_id.required' => 'bundle_id不能为空',
                'pid.required' => 'pid不能为空',
            ];
            $this->verifyParams($data, $rules, $message);

            $bundleIdPid = BundleIdPid::find($id);
            if (empty($bundleIdPid)) $this->throwExp(400, '查询不到相应数据');
            $bundleIdPid->bundle_id = $data['bundle_id'];
            $bundleIdPid->pid = $data['pid'];
            $bundleIdPid->sort = $data['sort'];
            if (!$bundleIdPid->save()) $this->throwExp(400, '修改失败');

            return handleResult(200, '修改成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * 删除包渠道
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            if (!intval($id)) $this->throwExp(400, '删除失败，参数错误');

            if (!BundleIdPid::destroy($id)) $this->throwExp(400, '删除失败');

            return handleResult(200, '删除成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }
}
