<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrangThaiDongBoQuanHuyen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quan_huyens', function (Blueprint $table) {
            $table->string('trang_thai_dong_bo')->default('chua_dong_bo');
            $table->string('ma_dinh_danh')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quan_huyens', function (Blueprint $table) {
            $table->dropColumn('trang_thai_dong_bo');
            $table->dropColumn('ma_dinh_danh');
        });
    }
}
