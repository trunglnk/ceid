<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuyetDinhThanhLapDoan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->text('ten')->nullable();
            $table->integer('nam_ke_hoach')->nullable();
            $table->string('ma_dinh_danh')->nullable();
            $table->string('trang_thai_dong_bo_ve')->nullable();
            $table->string('trang_thai_dong_bo_len')->default('chua_dong_bo');
            $table->dateTime('thoi_gian_gui_len')->nullable();
            $table->string('ten_co_quan_chu_tri')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->dropColumn('ten');
            $table->dropColumn('nam_ke_hoach');
            $table->dropColumn('ma_dinh_danh');
            $table->dropColumn('trang_thai_dong_bo_ve');
            $table->dropColumn('trang_thai_dong_bo_len');
            $table->dropColumn('thoi_gian_gui_len');
            $table->dropColumn('ten_co_quan_chu_tri');

        });
    }
}
