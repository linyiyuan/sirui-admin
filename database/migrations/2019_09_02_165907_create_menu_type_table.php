<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMenuTypeTable
 * @Author YiYuan-LIn
 * @Date: 2019/9/2
 * 菜品分类表
 */
class CreateMenuTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_type', function (Blueprint $table) {
            $table->increments('menu_type_id');
            $table->string('menu_type_name', 255)->default('')->comment('菜品分类名称');
            $table->tinyInteger('menu_type_status')->default(1)->comment('菜品分类状态 1:启用 0：禁用');
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
        Schema::dropIfExists('menu_type');
    }
}
