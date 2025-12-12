<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditThongBaoQuyetDinhThanhTrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thong_bao_quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->dropForeign('thong_bao_quyet_dinh_thanh_tras_ket_qua_thanh_tra_chung_id_fore');
            $table->dropColumn('ket_qua_thanh_tra_chung_id');
            $table->unsignedInteger('ket_qua_thanh_tra_id');
            $table->foreign('ket_qua_thanh_tra_id')->references('id')->on('ket_qua_thanh_tras');
        });          
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thong_bao_quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_chung_id')->nullable();
            $table->foreign('ket_qua_thanh_tra_chung_id')->references('id')->on('ket_qua_thanh_tra_chungs');
            $table->dropForeign('thong_bao_quyet_dinh_thanh_tras_ket_qua_thanh_tra_id_foreign');
            $table->dropColumn('ket_qua_thanh_tra_id');
        });  
    }
}
