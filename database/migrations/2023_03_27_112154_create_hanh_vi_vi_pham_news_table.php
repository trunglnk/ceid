<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHanhViViPhamNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hanh_vi_vi_pham_news', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('danh_muc_hanh_vi_id');
            $table->integer('xu_phat_chinh_id');

            $table->foreign('danh_muc_hanh_vi_id')
            ->references('id')
            ->on('danh_muc_hanh_vi_vi_phams')
            ->onDelete('cascade');

            $table->foreign('xu_phat_chinh_id')->references('id')->on('xu_phat_chinhs')->onDelete('cascade');; 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hanh_vi_vi_pham_news');
    }
}
