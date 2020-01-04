<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['namespace' => 'Api'],function(){
// ================== 不需要Token认证权限的路由 ==================
        //后台登录相关接口
        Route::group(['prefix' => 'auth', 'namespace' => 'Auth'] ,function() {
            Route::post('login', 'LoginController@login');      //验证登录接口
            Route::post('logout', 'LoginController@logOut');    //退出登录
        });
        //一些公共接口
        Route::group(['prefix' => 'common', 'namespace' => 'Common'], function() {
            Route::get('send_phone_code', 'SmsController@sendPhoneCode');         //发送短信验证码接口
        });

// ================== 需要Token认证的接口路由 ==================
        Route::middleware(['refresh.token'])->group(function(){
            //一些公共接口
            Route::group(['prefix' => 'common', 'namespace' => 'Common'], function() {
                Route::post('upload_pic', 'UploadController@uploadPic');                        //上传图片接口
                Route::post('upload_file', 'UploadController@uploadFile');                      //上传文件接口
            });

            Route::group(['prefix' => 'auth', 'namespace' => 'Auth'] ,function(){
                Route::get('getInfo', 'LoginController@getInfo');                       //获取用户信息接口
            });

            // ================== 需要权限认证的接口路由 ==================
            Route::middleware(['check.permission','record.operate'])->group(function(){
             

                //后台登录相关接口
                Route::group(['prefix' => 'auth', 'namespace' => 'Auth'] ,function(){
                    Route::resource('role', 'RoleController');                                          //角色控制器
                    Route::resource('permission', 'PermissionsController');                             //权限控制器
                    Route::resource('user', 'UserController');                                          //用户控制器
                    Route::post('reset_password', 'LoginController@resetPassword');                         //重置用户密码
                    Route::post('give_permission', 'LoginController@givePermission');                       //分配权限给角色
                });

                //系统管理
                Route::group(['prefix' => 'system', 'namespace' => 'System'], function() {
                    Route::get('get_config', 'ConfigController@getConfig');                                 //获取系统配置信息
                    Route::get('get_operate_log', 'OperateLogController@getList');                          //获取操作日志列表
                    Route::get('get_home_data', 'HomePageController@showData');                             //获取首页展示数据
                });

                //点餐系统
                Route::group(['prefix' => 'menu', 'namespace' => 'Menu'], function() {
                    Route::resource('menu', 'MenuController');                                          //菜单
                    Route::resource('menu_type', 'MenuTypeController');                                 //菜单分类
                    Route::resource('menu_config', 'MenuConfigController');                             //点餐配置
                    Route::resource('restaurant', 'RestaurantController');                              //餐馆分类
                    Route::post('order_menu', 'MenuRecordController@orderMenu');                             //下单操作
                    Route::delete('remove_menu_order', 'MenuRecordController@removeMenuOrder');                //下单操作
                    Route::get('get_menu_record_by_uid', 'MenuRecordController@getMenuRecordListByUid');     //根据UID获取订单记录
                    Route::get('get_menu_record_by_type', 'MenuRecordController@getMenuRecordListByType');   //根据点餐时间类型获取订单记录
                });

                //游戏用户
                Route::group(['prefix' => 'game', 'namespace' => 'Game'], function() {
                    Route::get('get_game_user_list', 'GameController@getGameUserList');                     //获取游戏用户列表
                    Route::get('get_recharge_rank', 'GameController@getRechargeRank');                      //获取用户充值信息
                    Route::get('get_order_info_by_account', 'GameController@getGameUserRechargeInfo');      //获取用户充值信息
                });

                //游戏中心
                Route::group(['prefix' => 'passport', 'namespace' => 'Passport'], function() {
                    Route::resource('version_review', 'VersionReviewController');                     //获取游戏用户列表
                    Route::get('agent_matching_record', 'AgentMatchingController@agentMatchingRecord');     //获取匹配记录
                    Route::get('agent_matching', 'AgentMatchingController@agentMatching');                  //获取匹配信息
                    Route::get('del_agent_matching_record', 'AgentMatchingController@delAgentMatchingRecordByCondition');
                    Route::resource('common_game', 'CommonGameController');                            //游戏设置
                    Route::resource('common_platform', 'CommonPlatformController');                    //游戏设置
                    Route::resource('common_channel', 'CommonChannelController');                      //游戏设置
                    Route::resource('bundle_id_pid', 'BundleIdPidController');                          //游戏设置
                });

                Route::get('test', 'TestController@test'); //测试接口

            });
        });
    });



