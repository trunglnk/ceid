<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHanhViViPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hanh_vi_vi_phams', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quyet_dinh_xu_phat_id');
            $table->foreign('quyet_dinh_xu_phat_id')->references('id')->on('quyet_dinh_xu_phats');
            $table->unsignedInteger('dieu_id');
            $table->foreign('dieu_id')->references('id')->on('dieus');
            $table->unsignedInteger('khoan_id');
            $table->foreign('khoan_id')->references('id')->on('khoans');
            $table->unsignedInteger('muc_id');
            $table->foreign('muc_id')->references('id')->on('mucs');
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
        Schema::dropIfExists('hanh_vi_vi_phams');
    }
}
