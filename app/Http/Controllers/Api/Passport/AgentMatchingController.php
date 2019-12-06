<?php

namespace App\Http\Controllers\Api\Passport;

use App\Http\Controllers\Api\CommonController;
use App\Models\Passport\AgentMatching;
use App\Models\Passport\AgentMatchingRecord;
use Illuminate\Support\Facades\Input;

class AgentMatchingController extends CommonController
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
     * 匹配信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function agentMatching()
    {
        try {
            $query = AgentMatching::query();

            if (strlen($this->params['affix'] ?? '') > 0) $query->where('affix', $this->params['affix']);
            if (strlen($this->params['ip'] ?? '') > 0) $query->where('ip', $this->params['ip']);
            if (strlen($this->params['phone'] ?? '') > 0) $query->where('phone', 'like' , '%' . $this->params['phone'] . '%');
            if (strlen($this->params['model'] ?? '') > 0) $query->where('model', 'like' , '%' . $this->params['model'] . '%');
            if (strlen($this->params['os'] ?? '') > 0) $query->where('os', $this->params['os']);

            $query->orderBy('addTime', 'desc');
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
     * 获取匹配记录
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function agentMatchingRecord()
    {
        try {
            $query = AgentMatchingRecord::query();

            if (!empty($this->params['ip'])) $query->where('ip', $this->params['ip']);
            if (!empty($this->params['add_date'])) $query->where('add_date', $this->params['add_date']);
            if (strlen($this->params['status'] ?? '') > 0) $query->where('status', $this->params['status']);

            $query->orderBy('add_time', 'desc');
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

}
