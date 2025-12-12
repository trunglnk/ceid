<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColToNhomHanhViViPhams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->boolean('khong_co_bao_cao_dtm')->default(false);
            $table->unsignedInteger('phat_tang_them_id')->nullable();
            $table->foreign('phat_tang_them_id')
                ->references('id')
                ->on('phat_tang_thems');
            $table->float('thong_so_vuot_chuan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->dropColumn('khong_co_bao_cao_dtm');
            $table->dropColumn('phat_tang_them_id');
            $table->dropColumn('thong_so_vuot_chuan');
        });
    }
}
