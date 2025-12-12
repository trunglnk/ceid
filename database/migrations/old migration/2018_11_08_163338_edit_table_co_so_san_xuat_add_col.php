<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTableCoSoSanXuatAddCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->date('ngay_cap_giay_chung_nhan_kinh_doanh')->nullable();
            $table->string('so_giay_chung_nhan_kinh_doanh')->references('id')->nullable();
            $table->unsignedInteger('co_quan_cap_giay_chung_nhan_kinh_doanh')->nullable();
            $table->foreign('co_quan_cap_giay_chung_nhan_kinh_doanh')->references('id')->on('co_quan_to_chucs');
            $table->dropColumn('co_quan_cap_giay_chung_nhan_dau_tu');
            $table->dropColumn('don_vi_cong_suat_hoat_dong');
            $table->dropColumn('don_vi_cong_suat_thiet_ke');
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
            $table->dropColumn('ngay_cap_giay_chung_nhan_kinh_doanh');
            $table->dropColumn('so_giay_chung_nhan_kinh_doanh');
            $table->dropColumn('co_quan_cap_giay_chung_nhan_kinh_doanh');
            $table->string('co_quan_cap_giay_chung_nhan_dau_tu');
            $table->unsignedInteger('don_vi_cong_suat_hoat_dong');
            $table->unsignedInteger('don_vi_cong_suat_thiet_ke');
        });
    }
}
