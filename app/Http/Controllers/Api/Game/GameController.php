<?php

namespace App\Http\Controllers\Api\Game;

use App\Http\Controllers\Api\CommonController;
use App\Models\Game\GameOrder;
use App\Models\Game\GameOrderRank;
use App\Models\Game\GameUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;

class GameController extends CommonController
{
    /**
     * 请求参数
     * @var array
     */
    private $params;

    /**
     * MenuController constructor.
     */
    public function __construct()
    {
        $this->params = Input::all();
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/10/18
     * @enumeration:
     * @return mixed
     * @description 获取游戏用户接口
     */
    public function getGameUserList()
    {
        try {
            $pageInfo['cur_page']   = $this->params['cur_page'] ?? 1;
            $pageInfo['page_size']  = $this->params['page_size'] ?? 15;
            $account = $this->params['account'] ?? 0;

            $list = GameUser::getGameUserList($account, $pageInfo);

            return handleResult(200, $list);
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/10/18
     * @enumeration:
     * @return \Illuminate\Http\JsonResponse
     * @description 获取用户充值详情
     */
    public function getGameUserRechargeInfo()
    {
        try {
            $account = $this->params['account'] ?? '';
            if (empty($account)) $this->throwExp('500', '查询用户账户不能为空');

            $orderList = GameOrder::getOrderInfoByAccount($account);
            $orderList = objToArray($orderList);

            $recharge_amount = array_column($orderList, 'order_amount');
            $recharge_sum = array_sum($recharge_amount);

            $orderInfo = [];
            foreach ($orderList as $key => $val) {
                $date = date('Y-m', strtotime($val['add_date']));
                if (empty($orderInfo[$date])) {
                    $orderInfo[$date]['amount'] = $val['order_amount'];
                    $orderInfo[$date]['date'] = $date;
                } else {
                    $orderInfo[$date]['amount'] += $val['order_amount'];
                }
            }
            $orderInfo['充值总额']['date'] = '--';
            $orderInfo['充值总额']['amount'] = $recharge_sum;

            ksort($orderInfo);
            $orderInfo = array_values($orderInfo);

            return handleResult(200, [
                'list' => [
                    'recharge_sum' => $recharge_sum,
                    'order_info_by_date' => $orderInfo
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    public function getRechargeRank()
    {
        try {
            $pageInfo['cur_page']   = $this->params['cur_page'] ?? 1;
            $pageInfo['page_size']  = $this->params['page_size'] ?? 15;

            $list = GameOrderRank::getGameOrderRankList($pageInfo);

            return handleResult(200, $list);
        }catch (\Exception $e) {
            return $this->errorExp($e);
        }

    }

}
