<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveRelationshipCoSoSanXuat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
           $table->dropForeign('ket_qua_thanh_tra_chat_thai_nguy_hais_co_so_san_xuat_id_foreign');
        });
          Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
           $table->dropForeign('ket_qua_thanh_tra_chat_thai_ran_cntts_co_so_san_xuat_id_foreign');
        });
          Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
           $table->dropForeign('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats_co_so_san_xuat_id_foreign');
        });
          Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
           $table->dropForeign('ket_qua_thanh_tra_khi_thais_co_so_san_xuat_id_foreign');
        });

          Schema::table('ket_qua_thanh_tra_nuoc_thais', function (Blueprint $table) {
           $table->dropForeign('ket_qua_thanh_tra_nuoc_thais_co_so_san_xuat_id_foreign');
        });
          Schema::table('ket_qua_thanh_tra_thu_tuc_hanh_chinhs', function (Blueprint $table) {
           $table->dropForeign('ket_qua_thanh_tra_thu_tuc_hanh_chinhs_co_so_san_xuat_id_foreign');
        });
          Schema::table('thong_bao_quyet_dinh_thanh_tras', function (Blueprint $table) {
           $table->dropForeign('thong_bao_quyet_dinh_thanh_tras_co_so_san_xuat_id_foreign');
        });
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
           $table->dropForeign('quyet_dinh_xu_phats_co_so_san_xuat_id_foreign');
        });

         Schema::table('chi_tiet_co_so_thanh_tra', function (Blueprint $table) {
           $table->dropForeign('chi_tiet_co_so_thanh_tra_co_so_san_xuat_id_foreign');
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
          $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
          $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');

        });Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
          $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
        });
         Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
          $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
        });
        Schema::table('ket_qua_thanh_tra_nuoc_thais', function (Blueprint $table) {
          $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');

        });Schema::table('ket_qua_thanh_tra_thu_tuc_hanh_chinhs', function (Blueprint $table) {
          $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
        });
         Schema::table('thong_bao_quyet_dinh_thanh_tras', function (Blueprint $table) {
          $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
        });
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
          $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
          
        });Schema::table('chi_tiet_co_so_thanh_tra', function (Blueprint $table) {
          $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
        });
    }
}