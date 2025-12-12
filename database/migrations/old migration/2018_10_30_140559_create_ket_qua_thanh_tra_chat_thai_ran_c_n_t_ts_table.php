<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetQuaThanhTraChatThaiRanCNTTsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ket_qua_thanh_tra_id');
            $table->foreign('ket_qua_thanh_tra_id')->references('id')->on('ket_qua_thanh_tras');
            $table->string('khoi_luong_phat_sinh')->nullable();
            $table->string('thanh_phan_chinh')->nullable();
            $table->boolean('tu_xu_ly')->default(true);
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
        Schema::dropIfExists('ket_qua_thanh_tra_chat_thai_ran_cntts');
    }
}
