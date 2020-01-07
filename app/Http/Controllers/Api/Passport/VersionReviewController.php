<?php

namespace App\Http\Controllers\Api\Passport;

use App\Http\Controllers\Api\CommonController;
use App\Models\Passport\VersionReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

/**
 * 审核开关控制器
 * Class VersionReviewController
 * @package App\Http\Controllers\Api\Passport
 * @Author YiYuan-LIn
 * @Date: 2019/12/5
 */
class VersionReviewController extends CommonController
{
    /**
     * 请求参数
     * @var
     */
    protected $params;

    /**
     * MenuConfigController constructor.
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
     * @description 获取审核开关
     */
    public function index()
    {
        try {
            $type = $this->params['type'] ?? '';
            $query = VersionReview::query();

            //判断是否取最大值
            if($type == 1) {
                $query = $query->select(DB::raw('max(bundle_version) as bundle_version, id, bundle_id, version_review_status, default_pid, created_at, updated_at, bundle_desc'));
                $query = $query->groupBy('bundle_id');
                $list = $query->get()->toArray();
                $total = count($list);
            }else{
                $total = $query->count();

                $query = $this->pagingCondition($query, $this->params);
                $list = $query->get()->toArray();
            }


            foreach ($list as $key => $val) {
                $list[$key]['pidCount'] = DB::table('bundle_id_pid')->where('bundle_id', $val['bundle_id'])->count();
            }

            return handleResult(200, [
                'list' => $list,
                'total' => $total
            ]);
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/6
     * @enumeration:
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @description 添加
     */
    public function store(Request $request)
    {
        try {
            $postData = $this->params['postData'] ?? $this->throwExp(400, '请求参数为空');

            $data = [
                'bundle_id' => $postData['bundle_id'],
                'bundle_version' => $postData['bundle_version'],
                'version_review_status' => $postData['version_review_status'] ?? 0,
                'default_pid' => $postData['default_pid'] ?? 0,
                'bundle_desc' => $postData['bundle_desc'] ?? '',
            ];
            $rules = [
                'bundle_id'  => 'required',
                'bundle_version'  => 'required',
            ];
            $message = [
                'bundle_id.required' => 'bundle_id不能为空',
                'bundle_version.required' => 'bundle_version不能为空',
            ];
            $this->verifyParams($data, $rules, $message);

            $versionReview = new VersionReview();
            $versionReview->bundle_id = $data['bundle_id'];
            $versionReview->bundle_version = $data['bundle_version'];
            $versionReview->version_review_status = $data['version_review_status'];
            $versionReview->default_pid = $data['default_pid'];
            $versionReview->bundle_desc = $data['bundle_desc'];

            if (!$versionReview->save()) $this->throwExp(400, '添加审核开关失败');

            return handleResult(200, '添加审核开关成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/6
     * @enumeration:
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @description 修改配置
     */
    public function update(Request $request, $id)
    {
        try {

            $postData = $this->params['postData'] ?? $this->throwExp(400, '请求参数为空');

            $data = [
                'id' => $id,
                'bundle_id' => $postData['bundle_id'],
                'bundle_version' => $postData['bundle_version'],
                'version_review_status' => $postData['version_review_status'] ?? 0,
                'default_pid' => $postData['default_pid'] ?? 0,
                'bundle_desc' => $postData['bundle_desc'] ?? '',
            ];
            $rules = [
                'id'  => 'required|integer',
                'bundle_id'  => 'required',
                'bundle_version'  => 'required',
            ];
            $message = [
                'id.required' => 'id不能为空',
                'id.integer' => 'id必须为整型',
                'bundle_id.required' => 'bundle_id不能为空',
                'bundle_version.required' => 'bundle_version不能为空',
            ];
            $this->verifyParams($data, $rules, $message);

            $versionReview = VersionReview::find($id);
            if (empty($versionReview)) $this->throwExp(400, '查询不到相应数据');
            $versionReview->bundle_id = $data['bundle_id'];
            $versionReview->bundle_version = $data['bundle_version'];
            $versionReview->version_review_status = $data['version_review_status'];
            $versionReview->default_pid = $data['default_pid'];
            $versionReview->bundle_desc = $data['bundle_desc'];
            if (!$versionReview->save()) $this->throwExp(400, '修改失败');

            return handleResult(200, '修改成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * 修改审核开关
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        try {
            if (!intval($id)) $this->throwExp(400, '修改状态失败，参数错误');

            $versionReview = VersionReview::find($id);
            if (empty($versionReview)) $this->throwExp(400, '查询不到该数据');

            if ($versionReview->version_review_status == 1) {
                $versionReview->version_review_status = 0;
            }else{
                $versionReview->version_review_status = 1;
            }

            $versionReview->save();

            return handleResult(200, '修改审核开关成功');
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
     * @description 删除
     */
    public function destroy($id)
    {
        try {
            if (!intval($id)) $this->throwExp(400, '删除失败，参数错误');

            if (!VersionReview::destroy($id)) $this->throwExp(400, '删除失败');

            return handleResult(200, '删除成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }
}
