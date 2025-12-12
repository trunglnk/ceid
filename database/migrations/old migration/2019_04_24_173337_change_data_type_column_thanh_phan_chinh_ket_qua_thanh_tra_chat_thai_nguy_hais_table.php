<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDataTypeColumnThanhPhanChinhKetQuaThanhTraChatThaiNguyHaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->string('thanh_phan_chinh', 5000)->change();
            $table->string('khoi_luong_phat_sinh_thuc_te', 5000)->change();
            $table->string('khoi_luong_phat_sinh_theo_so_dang_ki', 5000)->change();
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
            $table->string('thanh_phan_chinh', 255)->change();
            $table->string('khoi_luong_phat_sinh_thuc_te', 255)->change();
            $table->string('khoi_luong_phat_sinh_theo_so_dang_ki', 255)->change();
        });
    }
}
