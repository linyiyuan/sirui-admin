<?php

namespace App\Http\Controllers\Api\Passport;

use App\Http\Controllers\Api\CommonController;
use App\Models\Passport\CommonChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CommonChannelController extends CommonController
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
            if (empty($this->params['platform_id'])) $this->throwExp(400, '请选择渠道ID');
            $query = CommonChannel::query();

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
                'platform_id' => $postData['platform_id'] ?? '',
                'resource_id' => $postData['resource_id'] ?? '',
                'channel_id' => $postData['channel_id'] ?? '',
                'channel_name' => $postData['channel_name'] ?? '',
                'api_url' => $postData['api_url'] ?? '',
            ];
            $rules = [
                'platform_id'  => 'required',
                'resource_id'  => 'required',
                'channel_id'  => 'required',
                'channel_name'  => 'required',
                'api_url'  => 'required',
            ];
            $message = [
                'platform_id.required' => 'platform_id 不能为空',
                'resource_id.required' => 'resource_id 不能为空',
                'channel_id.required' => 'channel_id 不能为空',
                'channel_name.required' => 'channel_name 不能为空',
                'api_url.required' => 'api_url 不能为空',
            ];
            $this->verifyParams($data, $rules, $message);

            $commonChannel = new CommonChannel();
            $commonChannel ->game_id = $data['game_id'];
            $commonChannel ->platform_id = $data['platform_id'];
            $commonChannel ->platform_name = $data['platform_name'];

            if (!$commonChannel ->save()) $this->throwExp(400, '添加失败');

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
                'platform_id' => $postData['platform_id'] ?? '',
                'resource_id' => $postData['resource_id'] ?? '',
                'channel_id' => $postData['channel_id'] ?? '',
                'channel_name' => $postData['channel_name'] ?? '',
                'api_url' => $postData['api_url'] ?? '',
            ];
            $rules = [
                'platform_id'  => 'required',
                'resource_id'  => 'required',
                'channel_id'  => 'required',
                'channel_name'  => 'required',
                'api_url'  => 'required',
            ];
            $message = [
                'platform_id.required' => 'platform_id 不能为空',
                'resource_id.required' => 'resource_id 不能为空',
                'channel_id.required' => 'channel_id 不能为空',
                'channel_name.required' => 'channel_name 不能为空',
                'api_url.required' => 'api_url 不能为空',
            ];
            $this->verifyParams($data, $rules, $message);

            $commonChannel = CommonChannel::query()->where('resource_id', $data['resource_id'])->first();
            $commonChannel ->platform_id = $data['platform_id'];
            $commonChannel ->resource_id = $data['resource_id'];
            $commonChannel ->channel_id = $data['channel_id'];
            $commonChannel ->channel_name = $data['channel_name'];
            $commonChannel ->api_url = $data['api_url'];

            if (!$commonChannel ->save()) $this->throwExp(400, '添加失败');

            return handleResult(200, '添加成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }
}
