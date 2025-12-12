<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnKetQuaThanhTraChungIdKetLuanSauThanhTraGroupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thong_bao_quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_chung_id')->nullable();
            $table->foreign('ket_qua_thanh_tra_chung_id')->references('id')->on('ket_qua_thanh_tra_chungs');
        });        
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_chung_id')->nullable();
            $table->foreign('ket_qua_thanh_tra_chung_id')->references('id')->on('ket_qua_thanh_tra_chungs');
        });
        Schema::table('khac_phuc_hau_quas', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_chung_id')->nullable();
            $table->foreign('ket_qua_thanh_tra_chung_id')->references('id')->on('ket_qua_thanh_tra_chungs');
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
            $table->dropColumn('ket_qua_thanh_tra_chung_id');  
        });
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_chung_id');  
        });
        Schema::table('khac_phuc_hau_quas', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_chung_id');  
        });
    }
}
