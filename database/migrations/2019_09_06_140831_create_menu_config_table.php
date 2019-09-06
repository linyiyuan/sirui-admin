<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_config', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('order_switch')->default(1)->comment('点餐总开关');
            $table->integer('time_range')->default(1)->comment('点餐时间范围（周）');
            $table->integer('order_max_amount')->default(15)->comment('下单最大金额');
            $table->tinyInteger('breakfast_switch')->default(0)->comment('早餐点餐开关');
            $table->tinyInteger('lunch_switch')->default(0)->comment('午餐点餐开关');
            $table->tinyInteger('dinner_switch')->default(0)->comment('晚餐点餐开关');
            $table->tinyInteger('snack_switch')->default(0)->comment('下午茶点餐开关');
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
        Schema::dropIfExists('menu_config');
    }
}
