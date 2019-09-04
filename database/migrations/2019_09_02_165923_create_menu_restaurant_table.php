<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMenuRestaurantTable
 * @Author YiYuan-LIn
 * @Date: 2019/9/2
 * 餐厅表
 */
class CreateMenuRestaurantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant', function (Blueprint $table) {
            $table->increments('restaurant_id');
            $table->string('restaurant_name', 255)->default('')->comment('餐厅名');
            $table->tinyInteger('restaurant_status')->default(1)->comment('餐厅状态 1:启动 0:禁用');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_restaurant');
    }
}
