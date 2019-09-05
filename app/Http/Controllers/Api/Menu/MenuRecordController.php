<?php

namespace App\Http\Controllers\Api\Menu;

use App\Http\Controllers\Api\CommonController;
use App\Http\Services\MenuService;
use Illuminate\Support\Facades\Input;


class MenuRecordController extends CommonController
{
    /**
     * 请求参数
     * @var
     */
    protected $params;

    /**
     * MenuRecordController constructor.
     */
    public function __construct()
    {
        $this->params = Input::all();
    }


    public function orderMenu()
    {
        try {
            $postData = $this->params['postData'] ?? $this->throwExp(400, '请求参数为空');

            $data = [
                'menu_id' => $postData['menu_id'] ?? '',
                'amount' => $postData['amount'] ?? 0,
            ];

            $rules = [
                'menu_id' => 'required',
                'amount' => 'required|integer',
            ];

            $message = [
                'menu_id.required'  => '请选择菜单',
                'amount.required'  => '金额不允许为空',
                'amount.integer'  => '金额不允许为空',
            ];

            $this->verifyParams($data, $rules, $message);

            $result = MenuService::getInstance()->orderMenu($data);

            if (!$result) $this->throwExp(400, '下单失败');

            return handleResult(200, $result);
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

}
