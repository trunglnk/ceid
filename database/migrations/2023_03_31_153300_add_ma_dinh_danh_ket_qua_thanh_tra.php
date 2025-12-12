<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaDinhDanhKetQuaThanhTra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tras', function (Blueprint $table) {
            $table->string('ma_dinh_danh')->nullable();
            $table->dateTime('thoi_gian_dong_bo')->nullable();
            $table->string('trang_thai_dong_bo')->nullable();
        });
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->string('ma_dinh_danh')->nullable();
            $table->dateTime('thoi_gian_dong_bo')->nullable();
            $table->string('trang_thai_dong_bo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ket_qua_thanh_tras', function (Blueprint $table) {
            $table->dropColumn('ma_dinh_danh');
            $table->dropColumn('thoi_gian_dong_bo');
            $table->dropColumn('trang_thai_dong_bo');
        });
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->dropColumn('ma_dinh_danh');
            $table->dropColumn('thoi_gian_dong_bo');
            $table->dropColumn('trang_thai_dong_bo');
        });
    }
}
