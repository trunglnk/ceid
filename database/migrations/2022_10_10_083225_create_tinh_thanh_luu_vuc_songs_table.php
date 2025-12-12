<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTinhThanhLuuVucSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tinh_thanh_luu_vuc_songs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('tinh_thanh_id')->nullable();
            $table->foreign('tinh_thanh_id')
            ->references('id')
            ->on('tinh_thanhs')
            ->onDelete('cascade');
            $table->integer('quan_huyen_id')->nullable();
            $table->foreign('quan_huyen_id')
            ->references('id')
            ->on('quan_huyens')
            ->onDelete('cascade');
            $table->integer('luu_vuc_song_id')->nullable();
            $table->foreign('luu_vuc_song_id')
            ->references('id')
            ->on('luu_vuc_songs')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tinh_thanh_luu_vuc_songs');
    }
}
