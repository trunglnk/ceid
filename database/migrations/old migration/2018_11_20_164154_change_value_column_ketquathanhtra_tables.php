<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeValueColumnKetquathanhtraTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $table->dropColumn('khoi_luong_phat_sinh');
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $table->double('khoi_luong_phat_sinh')->nullable();
        });

        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->dropColumn('khoi_luong_phat_sinh');
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->double('khoi_luong_phat_sinh')->nullable();
        });

        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->dropColumn('khoi_luong_phat_sinh_thuc_te');
            $table->dropColumn('khoi_luong_phat_sinh_theo_so_dang_ki');
        });

        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->double('khoi_luong_phat_sinh_theo_so_dang_ki')->nullable();
            $table->double('khoi_luong_phat_sinh_thuc_te')->nullable();
        });
       
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $table->dropColumn('khoi_luong_phat_sinh');
        });

        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $ $table->string('khoi_luong_phat_sinh')->nullable();
        });

        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->dropColumn('khoi_luong_phat_sinh');
        });

        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $ $table->string('khoi_luong_phat_sinh')->nullable();
        });

        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->dropColumn('khoi_luong_phat_sinh_thuc_te');
            $table->dropColumn('khoi_luong_phat_sinh_theo_so_dang_ki');
        });

        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->string('khoi_luong_phat_sinh_theo_so_dang_ki')->nullable();
            $table->string('khoi_luong_phat_sinh_thuc_te')->nullable();
        });
       
    }
}
