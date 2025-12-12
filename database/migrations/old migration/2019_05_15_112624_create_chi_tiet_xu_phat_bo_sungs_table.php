<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChiTietXuPhatBoSungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_xu_phat_bo_sungs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('xu_phat_bo_sung_id')->nullable();
            $table->foreign('xu_phat_bo_sung_id')->references('id')->on('xu_phat_bo_sungs');
            $table->unsignedInteger('loai_xu_phat_bo_sung_id')->nullable();
            $table->foreign('loai_xu_phat_bo_sung_id')->references('id')->on('loai_xu_phat_bo_sungs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chi_tiet_xu_phat_bo_sungs');
    }
}
