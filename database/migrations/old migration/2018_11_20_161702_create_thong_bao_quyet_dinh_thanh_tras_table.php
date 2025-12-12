<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThongBaoQuyetDinhThanhTrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thong_bao_quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('so_thong_bao_quyet_dinh')->nullable();
            $table->unsignedInteger('co_quan_thong_bao_id')->nullable();
            $table->foreign('co_quan_thong_bao_id')->references('id')->on('co_quan_to_chucs');
            $table->unsignedInteger('co_so_san_xuat_id');
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
            $table->date('thoi_gian')->nullable();
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
        Schema::dropIfExists('thong_bao_quyet_dinh_thanh_tras');
    }
}
