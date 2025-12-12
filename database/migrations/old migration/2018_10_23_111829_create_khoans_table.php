<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKhoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khoans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten')->nullable();            
            $table->text('mo_ta')->nullable();  
            $table->unsignedInteger('dieu_id');
            $table->foreign('dieu_id')->references('id')->on('dieus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('khoans');
    }
}
