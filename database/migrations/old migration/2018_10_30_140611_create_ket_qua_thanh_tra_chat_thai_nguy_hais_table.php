<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetQuaThanhTraChatThaiNguyHaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ket_qua_thanh_tra_id');
            $table->foreign('ket_qua_thanh_tra_id')->references('id')->on('ket_qua_thanh_tras');
            $table->string('khoi_luong_phat_sinh_thuc_te')->nullable();
            $table->string('khoi_luong_phat_sinh_theo_so_dang_ki')->nullable();
            $table->string('thanh_phan_chinh')->nullable();
            $table->boolean('tu_xu_ly_ctrsh')->default(true);
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
        Schema::dropIfExists('ket_qua_thanh_tra_chat_thai_nguy_hais');
    }
}
