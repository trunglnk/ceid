<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMucsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mucs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten')->nullable();            
            $table->text('mo_ta')->nullable();  
            $table->unsignedInteger('khoan_id');
            $table->foreign('khoan_id')->references('id')->on('khoans');
            $table->double('muc_phat_nho_nhat', 10, 0)->nullable();
            $table->double('muc_phat_lon_nhat', 10, 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mucs');
    }
}
