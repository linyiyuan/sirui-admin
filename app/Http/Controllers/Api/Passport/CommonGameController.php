<?php

namespace App\Http\Controllers\Api\Passport;

use App\Http\Controllers\Api\CommonController;
use App\Models\Passport\CommonGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * Class CommonGameController
 * @package App\Http\Controllers\Api\Passport
 * @Author YiYuan-LIn
 * @Date: 2019/12/6
 */
class CommonGameController extends CommonController
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

    public function index()
    {
        try {
            $query = CommonGame::query();

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
                'game_id' => $postData['game_id'] ?? '',
                'game_name' => $postData['game_name'] ?? '',
                'game_icon' => $postData['game_icon'] ?? '',
                'game_secret' => $postData['game_secret'] ?? '',
                'game_status' => $postData['game_status'] ?? 0,
            ];
            $rules = [
                'game_id'  => 'required',
                'game_name'  => 'required',
                'game_secret'  => 'required',
            ];
            $message = [
                'game_id.required' => 'game_id 不能为空',
                'game_name.required' => 'game_name 不能为空',
                'game_secret.required' => 'game_secret 不能为空',
            ];
            $this->verifyParams($data, $rules, $message);

            $commonGame = new CommonGame();
            $commonGame ->game_id = $data['game_id'];
            $commonGame ->game_name = $data['game_name'];
            $commonGame ->game_icon = $data['game_icon'];
            $commonGame ->game_secret = $data['game_secret'];
            $commonGame ->game_status = $data['game_status'];

            if (!$commonGame ->save()) $this->throwExp(400, '添加失败');

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
                'game_name' => $postData['game_name'] ?? '',
                'game_icon' => $postData['game_icon'] ?? '',
                'game_status' => $postData['game_status'] ?? 0,
            ];
            $rules = [
                'game_id'  => 'required',
                'game_name'  => 'required',
            ];
            $message = [
                'game_id.required' => 'game_id 不能为空',
                'game_name.required' => 'game_name 不能为空',
            ];
            $this->verifyParams($data, $rules, $message);

            $commonGame = CommonGame::query()->where('game_id', $data['game_id'])->first();
            if (empty($commonGame)) $this->throwExp(400, '查询不到该数据');
            $commonGame->game_name = $data['game_name'];
            $commonGame->game_icon = $data['game_icon'];
            $commonGame->game_status = $data['game_status'];

            if (!$commonGame ->save()) $this->throwExp(400, '修改失败');

            return handleResult(200, '修改成功');
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }
}
