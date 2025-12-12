<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToKetQuaThanhTraChungLoaiHinhHoatDongs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs', function (Blueprint $table) {
            $table->text('ghi_chu')->nullable()->after('thong_so_thiet_ke');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs', function (Blueprint $table) {
            $table->dropColumn('ghi_chu');
        });
    }
}
