<?php
namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class MenuService
 * @package App\Http\Services
 * @Author YiYuan-LIn
 * @Date: 2019/9/5
 * 下单操作服务类
 */
class MenuService extends BaseService
{

    public function orderMenu($menu_id, $amount)
    {
        if (empty($menu_id)) return false;

        //获取当前日期
        $nowDate = date('Y-m-d');

        //获取当前用户
        $user = JWTAuth::parseToken()->toUser();





    }
}