<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetQuaThanhTraChungLoaiHinhHoatDongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ket_qua_thanh_tra_chung_id')->nullable();
            $table->foreign('ket_qua_thanh_tra_chung_id')->references('id')->on('ket_qua_thanh_tra_chungs');
            $table->unsignedInteger('loai_hinh_co_so_id')->nullable();
            $table->foreign('loai_hinh_co_so_id')->references('id')->on('loai_hinh_co_sos');
            $table->unsignedInteger('don_vi_hoat_dong_id')->nullable();
            $table->foreign('don_vi_hoat_dong_id')->references('id')->on('chuyen_doi_don_vis');
            $table->double('thong_so_hoat_dong')->nullable();
            $table->unsignedInteger('don_vi_thiet_ke_id')->nullable();
            $table->foreign('don_vi_thiet_ke_id')->references('id')->on('chuyen_doi_don_vis');
            $table->double('thong_so_thiet_ke')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs');
    }
}
