<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_record', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用户ID');
            $table->integer('menu_id')->comment('菜品关联ID');
            $table->integer('amount')->default(0)->comment('点餐价格');
            $table->date('addDate')->comment('点餐日期');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_record');
    }
}
