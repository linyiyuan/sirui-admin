<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMenuTable
 * @Author YiYuan-LIn
 * @Date: 2019/9/2
 * 菜谱表
 */
class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('menu_id');
            $table->string('menu_name', 255)->default('')->comment('菜品名称');
            $table->integer('menu_amount')->default(0)->comment('菜品金额');
            $table->tinyInteger('menu_status')->default(1)->comment('菜品状态 1：启用 0：禁用');
            $table->integer('restaurant_id')->comment('餐厅关联ID');
            $table->integer('menu_type_id')->comment('菜品分类关联');
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
        Schema::dropIfExists('menu');
    }
}
