<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToNhomHanhViViPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function ($table) {
            $table->string('ma_loai_hinh_quan_trac')->nullable();
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function ($table) {
            $table->dropColumn('ma_loai_hinh_quan_trac');
            $table->dropColumn('ket_qua_thong_so_vuot_chuan_nuoc_thai');
            $table->dropColumn('ket_qua_thong_so_vuot_chuan_khi_thai');
            $table->dropColumn('don_vi_thong_so_vuot_chuan_nuoc_thai_id');
            $table->dropColumn('don_vi_thong_so_vuot_chuan_khi_thai_id');
            $table->dropColumn('so_lan_vuot_chuan_nuoc_thai');
            $table->dropColumn('so_lan_vuot_chuan_khi_thai');
        });
    }
}
