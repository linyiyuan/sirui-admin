<?php

namespace App\Http\Controllers\Api\Menu;

use App\Http\Controllers\Api\CommonController;
use App\Models\Menu\MenuConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use function Qiniu\thumbnail;

class MenuConfigController extends CommonController
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
     * @description 获取菜单配置列表
     */
    public function index()
    {
        try {
            $menuConfigList = MenuConfig::getMenuConfig();

            return handleResult(200, [
                'list' => $menuConfigList,
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
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @description 修改配置
     */
    public function update(Request $request, $id)
    {
        $postData = $this->params['postData'] ?? $this->throwExp(400, '请求参数为空');

        $data = [
            'config_value' => $postData['config_value'] ?? '',
            'id' => $id,
        ];
        $rules = [
            'config_value'  => 'required',
            'id'  => 'required|integer',
        ];
        $message = [
            'config_value.required' => '配置值不能为空',
            'id.required' => 'ID不能为空',
            'id.integer' => 'ID类型错误',
        ];
        $this->verifyParams($data, $rules, $message);

        $menuConfig = MenuConfig::find($id);
        if (empty($message)) $this->throwExp(400, '查询不到该数据');

        $menuConfig->config_value = $data['config_value'];
        if (!$menuConfig->save()) $this->throwExp(400, '修改失败');

        return handleResult(200, '修改成功');
    }
}
