<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModels
 * @package App\Models
 * @Author YiYuan-LIn
 * @Date: 2019/9/4
 * 模型公共使用类
 */
class BaseModels extends Model
{
    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/4
     * @param $query
     * @param $condition
     * @return Model
     * @description 设置条件
     */
    public static function _setCondition($query, $condition)
    {
        if (empty($condition)) return $query;

        foreach ($condition as $key => $value) {
            $query = $query->where($key, $value);
        }

        return $query;
    }

    /**'
     * @Author YiYuan-LIn
     * @Date: 2019/9/4
     * @param $query
     * @param $condition
     * @return mixed
     * @description 设置排序
     */
    public static function _setSort($query, $condition)
    {
        if (empty($condition)) return $query;

        foreach ($condition as $key => $value) {
            $query = $query->orderBy($key, $value);
        }

        return $query;
    }

    /**
     * @Author YiYuan-LIn
     * @Date: 2019/9/4
     * @param $query
     * @param int $cur_page
     * @param int $page_size
     * @return mixed
     * @description 设置分页
     */
    public static function _pagingCondition($query, $cur_page = 1, $page_size = 15)
    {
        $cur_page   = $cur_page ?? 1;
        $page_size  = $page_size ?? 15;

        $offset = ($cur_page- 1) * $page_size;
        $limit  = $page_size;
        $query = $query->offset($offset)->limit($limit);

        return $query;
    }
}
