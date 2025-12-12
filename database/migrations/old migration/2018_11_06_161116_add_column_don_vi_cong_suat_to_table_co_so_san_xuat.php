<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDonViCongSuatToTableCoSoSanXuat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->unsignedInteger('don_vi_cong_suat_hoat_dong')->nullable();
            $table->foreign('don_vi_cong_suat_hoat_dong')->references('id')->on('chuyen_doi_don_vis');
            $table->unsignedInteger('don_vi_cong_suat_thiet_ke')->nullable();
            $table->foreign('don_vi_cong_suat_thiet_ke')->references('id')->on('chuyen_doi_don_vis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->dropColumn('don_vi_cong_suat_hoat_dong');
            $table->dropColumn('don_vi_cong_suat_thiet_ke');
        });
    }
}
