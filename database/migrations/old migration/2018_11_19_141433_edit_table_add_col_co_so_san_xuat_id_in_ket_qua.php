<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTableAddColCoSoSanXuatIdInKetQua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->unsignedInteger('co_so_san_xuat_id')->nullable();
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->unsignedInteger('co_so_san_xuat_id')->nullable();
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $table->unsignedInteger('co_so_san_xuat_id')->nullable();
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
        });
        Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
            $table->unsignedInteger('co_so_san_xuat_id')->nullable();
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
        });
        Schema::table('ket_qua_thanh_tra_nuoc_thais', function (Blueprint $table) {
            $table->unsignedInteger('co_so_san_xuat_id')->nullable();
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
        });
        Schema::table('ket_qua_thanh_tra_thu_tuc_hanh_chinhs', function (Blueprint $table) {
            $table->unsignedInteger('co_so_san_xuat_id')->nullable();
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
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
            $table->dropColumn('co_so_san_xuat_id');
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->dropColumn('co_so_san_xuat_id');
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $table->dropColumn('co_so_san_xuat_id');
        });
        Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
            $table->dropColumn('co_so_san_xuat_id');
        });
        Schema::table('ket_qua_thanh_tra_nuoc_thais', function (Blueprint $table) {
            $table->dropColumn('co_so_san_xuat_id');
        });
        Schema::table('ket_qua_thanh_tra_thu_tuc_hanh_chinhs', function (Blueprint $table) {
            $table->dropColumn('co_so_san_xuat_id');
        });
    }
}
