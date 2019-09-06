<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuRecordHasMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_record_has_menu', function (Blueprint $table)  {
            $table->unsignedInteger('menu_record_id');
            $table->unsignedInteger('menu_id');

            $table->foreign('menu_record_id')
                ->references('id')
                ->on('menu_record')
                ->onDelete('cascade');

            $table->foreign('menu_id')
                ->references('menu_id')
                ->on('menu')
                ->onDelete('cascade');

            $table->primary(['menu_record_id', 'menu_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_record_has_menu');
    }
}
