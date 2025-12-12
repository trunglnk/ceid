<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrangThaiDongBoThongSo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('danh_muc_thong_so_vuot_chuans', function (Blueprint $table) {
            $table->string('trang_thai_dong_bo')->default('chua_dong_bo');
            $table->string('ma')->nullable();
            $table->string('ma_loai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('danh_muc_thong_so_vuot_chuans', function (Blueprint $table) {
            $table->dropColumn('trang_thai_dong_bo');
            $table->dropColumn('ma');
            $table->dropColumn('ma_loai');
        });
    }
}
