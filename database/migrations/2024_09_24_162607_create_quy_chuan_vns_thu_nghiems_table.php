<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuyChuanVnsThuNghiemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quy_chuan_vns_thu_nghiems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('thu_nghiem_id');
            $table->foreign('thu_nghiem_id')
                ->references('id')->on('thu_nghiems')
                ->onDelete('cascade');

            $table->integer('quy_chuan_vn_id');
            $table->foreign('quy_chuan_vn_id')
                ->references('id')->on('quy_chuan_vns')
                ->onDelete('cascade');

            $table->string('gia_tri')->nullable();
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
        Schema::dropIfExists('quy_chuan_vns_thu_nghiems');
    }
}
