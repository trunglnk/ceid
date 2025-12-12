<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTableChiTietCongSuatChiTietThongSo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chi_tiet_cong_suats', function (Blueprint $table) {
            $table->unsignedInteger('don_vi_hoat_dong_id')->nullable();
            $table->foreign('don_vi_hoat_dong_id')->references('id')->on('chuyen_doi_don_vis');
            $table->double('thong_so_hoat_dong')->nullable();

        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chi_tiet_cong_suats', function (Blueprint $table) {
            $table->dropColumn('don_vi_hoat_dong_id');
            $table->dropColumn('thong_so_hoat_dong');
        });   
     }
}
