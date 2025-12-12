<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTableXuPhatBoXungs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('xu_phat_bo_xungs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('xu_phat_bo_xungs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quyet_dinh_xu_phat_id');
            $table->foreign('quyet_dinh_xu_phat_id')->references('id')->on('quyet_dinh_xu_phats');
            $table->unsignedInteger('xu_phat_bo_sung_id');
            $table->foreign('xu_phat_bo_sung_id')->references('id')->on('loai_xu_phat_bo_sungs');
        });
    }
}
