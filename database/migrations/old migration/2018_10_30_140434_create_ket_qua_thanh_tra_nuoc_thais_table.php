<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetQuaThanhTraNuocThaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_thanh_tra_nuoc_thais', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ket_qua_thanh_tra_id');
            $table->foreign('ket_qua_thanh_tra_id')->references('id')->on('ket_qua_thanh_tras');
            $table->double('luu_luong')->nullable();
            $table->double('cong_suat_he_thong_xu_ly')->nullable();
            $table->string('nguon_tiep_nhan', 1000)->nullable();
            $table->string('thanh_phan')->nullable();
            $table->double('thong_so_nuoc_thai_vuot_chuan')->nullable();
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
        Schema::dropIfExists('ket_qua_thanh_tra_nuoc_thais');
    }
}
