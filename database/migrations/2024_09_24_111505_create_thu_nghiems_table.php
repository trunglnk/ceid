<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThuNghiemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thu_nghiems', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stt');
            $table->string('thong_so');
            $table->string('don_vi')->nullable();
            $table->string('phuong_phap_thu');
            $table->string('ket_qua');
            $table->integer('phieu_thu_nghiem_id');
            $table->foreign('phieu_thu_nghiem_id')
                ->references('id')->on('phieu_thu_nghiems')
                ->onDelete('cascade');
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
        Schema::dropIfExists('thu_nghiems');
    }
}
