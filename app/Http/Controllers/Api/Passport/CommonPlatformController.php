<?php

namespace App\Http\Controllers\Api\Passport;

use App\Http\Controllers\Api\CommonController;
use App\Models\Passport\CommonPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CommonPlatformController extends CommonController
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
     * 列表展示
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            if (empty($this->params['game_id'])) $this->throwExp(400, '请选择游戏GameID');
            $query = CommonPlatform::query();

            $total = $query->count();
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
     * 添加
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $postData = $this->params['postData'] ?? $this->throwExp(400, '请求参数为空');

            $data = [
                'game_id' => $postData['game_id'] ?? '',
                'platform_id' => $postData['platform_id'] ?? '',
                'platform_name' => $postData['platform_name'] ?? '',
            ];
            $rules = [
                'game_id'  => 'required',
                'platform_id'  => 'required',
                'platform_name'  => 'required',
            ];
            $message = [
                'game_id.required' => 'game_id 不能为空',
                'platform_id.required' => 'platform_id 不能为空',
                'platform_name.required' => 'platform_name 不能为空',
            ];
            $this->verifyParams($data, $rules, $message);

            $commonPlatform = new CommonPlatform();
            $commonPlatform->game_id = $data['game_id'];
            $commonPlatform->platform_id = $data['platform_id'];
            $commonPlatform->platform_name = $data['platform_name'];

            if (!$commonPlatform ->save()) $this->throwExp(400, '添加失败');

            return handleResult(200, '添加成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * 修改
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $postData = $this->params['postData'] ?? $this->throwExp(400, '请求参数为空');

            $data = [
                'game_id' => $postData['game_id'] ?? '',
                'platform_id' => $postData['platform_id'] ?? '',
                'platform_name' => $postData['platform_name'] ?? '',
            ];
            $rules = [
                'game_id'  => 'required',
                'platform_id'  => 'required',
                'platform_name'  => 'required',
            ];
            $message = [
                'game_id.required' => 'game_id 不能为空',
                'platform_id.required' => 'platform_id 不能为空',
                'platform_name.required' => 'platform_name 不能为空',
            ];
            $this->verifyParams($data, $rules, $message);

            $commonPlatform = CommonPlatform::query()->where('platform_id', $data['platform_id'])->first();
            if (empty($commonPlatform)) $this->throwExp(400, '查询不到指定数据');
            $commonPlatform ->game_id = $data['game_id'];
            $commonPlatform ->platform_id = $data['platform_id'];
            $commonPlatform ->platform_name = $data['platform_name'];

            if (!$commonPlatform ->save()) $this->throwExp(400, '修改失败');

            return handleResult(200, '修改成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }
}
