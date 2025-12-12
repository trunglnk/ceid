<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteSomeColumnUselessToNhomHanhViViPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->dropColumn('thong_so_vuot_chuan_nuoc_thai_id');
            $table->dropColumn('thong_so_vuot_chuan_khi_thai_id');

            $table->dropColumn('ket_qua_thong_so_vuot_chuan_nuoc_thai');
            $table->dropColumn('ket_qua_thong_so_vuot_chuan_khi_thai');
            $table->dropColumn('don_vi_thong_so_vuot_chuan_nuoc_thai_id');
            $table->dropColumn('don_vi_thong_so_vuot_chuan_khi_thai_id');
            $table->dropColumn('so_lan_vuot_chuan_nuoc_thai');
            $table->dropColumn('so_lan_vuot_chuan_khi_thai');
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
            $table->string('thong_so_vuot_chuan_nuoc_thai_id')->nullable();
            $table->string('thong_so_vuot_chuan_khi_thai_id')->nullable();

            $table->float('ket_qua_thong_so_vuot_chuan_nuoc_thai')->nullable();
            $table->float('ket_qua_thong_so_vuot_chuan_khi_thai')->nullable();
            $table->float('so_lan_vuot_chuan_nuoc_thai')->nullable();
            $table->float('so_lan_vuot_chuan_khi_thai')->nullable();

            $table->unsignedInteger('don_vi_thong_so_vuot_chuan_nuoc_thai_id')->nullable();
            $table->foreign('don_vi_thong_so_vuot_chuan_nuoc_thai_id')
                ->references('id')
                ->on('chuyen_doi_don_vis');

            $table->unsignedInteger('don_vi_thong_so_vuot_chuan_khi_thai_id')->nullable();
            $table->foreign('don_vi_thong_so_vuot_chuan_khi_thai_id')
                ->references('id')
                ->on('chuyen_doi_don_vis');
        });
    }
}
