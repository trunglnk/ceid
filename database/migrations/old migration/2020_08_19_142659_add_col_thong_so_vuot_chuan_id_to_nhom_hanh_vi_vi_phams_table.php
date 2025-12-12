<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColThongSoVuotChuanIdToNhomHanhViViPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->string('thong_so_vuot_chuan_nuoc_thai_id')->nullable();
            $table->string('thong_so_vuot_chuan_khi_thai_id')->nullable();
            $table->dropColumn('nuoc_thai_thong_so_vuot_chuan');
            $table->dropColumn('khi_thai_thong_so_vuot_chuan');
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
            $table->dropColumn('thong_so_vuot_chuan_nuoc_thai_id');
            $table->dropColumn('thong_so_vuot_chuan_khi_thai_id');
            $table->string('nuoc_thai_thong_so_vuot_chuan');
            $table->string('khi_thai_thong_so_vuot_chuan');
        });
    }
}
