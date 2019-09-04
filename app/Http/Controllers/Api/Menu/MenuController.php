<?php

namespace App\Http\Controllers\Api\Menu;

use App\Http\Controllers\Api\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * Class MenuController
 * @package App\Http\Controllers\Api\Menu
 * @Author YiYuan-LIn
 * @Date: 2019/9/3
 * 菜品控制器
 */
class MenuController extends CommonController
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $list = [];

            //获取餐馆ID
            $restaurant_id = $this->params['restaurant_id'] ?? 0;



        }catch (\Exception $e) {
            return $this->errorExp($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
