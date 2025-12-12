<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetQuaThanhTraThuTucHanhChinhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_thanh_tra_thu_tuc_hanh_chinhs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ket_qua_thanh_tra_id');
            $table->foreign('ket_qua_thanh_tra_id')->references('id')->on('ket_qua_thanh_tras');
            $table->unsignedInteger('loai_thu_tuc_hanh_chinh_id');
            $table->foreign('loai_thu_tuc_hanh_chinh_id')->references('id')->on('loai_thu_tuc_hanh_chinhs');
            $table->string('so_quyet_dinh_phe_duyet')->nullable();
            $table->unsignedInteger('co_quan_phe_duyet_id')->nullable();
            $table->foreign('co_quan_phe_duyet_id')->references('id')->on('co_quan_to_chucs');
            $table->date('thoi_gian_phe_duyet')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('ket_qua_thanh_tra_thu_tuc_hanh_chinhs');
    }
}
