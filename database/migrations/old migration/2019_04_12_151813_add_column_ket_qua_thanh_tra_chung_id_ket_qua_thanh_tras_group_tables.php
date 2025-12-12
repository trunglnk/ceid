<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnKetQuaThanhTraChungIdKetQuaThanhTrasGroupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_chung_id')->nullable();
            $table->foreign('ket_qua_thanh_tra_chung_id')->references('id')->on('ket_qua_thanh_tra_chungs');
        });        
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_chung_id')->nullable();
            $table->foreign('ket_qua_thanh_tra_chung_id')->references('id')->on('ket_qua_thanh_tra_chungs');
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_chung_id')->nullable();
            $table->foreign('ket_qua_thanh_tra_chung_id')->references('id')->on('ket_qua_thanh_tra_chungs');
        });
        Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_chung_id')->nullable();
            $table->foreign('ket_qua_thanh_tra_chung_id')->references('id')->on('ket_qua_thanh_tra_chungs');
        });
        Schema::table('ket_qua_thanh_tra_nuoc_thais', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_chung_id')->nullable();
            $table->foreign('ket_qua_thanh_tra_chung_id')->references('id')->on('ket_qua_thanh_tra_chungs');
        });
        Schema::table('ket_qua_thanh_tra_thu_tuc_hanh_chinhs', function (Blueprint $table) {
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
        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_chung_id');  
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_chung_id');  
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_chung_id');  
        });
        Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_chung_id');  
        });
        Schema::table('ket_qua_thanh_tra_nuoc_thais', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_chung_id');  
        });
        Schema::table('ket_qua_thanh_tra_thu_tuc_hanh_chinhs', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_chung_id');  
        });
    }
}
