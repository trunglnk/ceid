<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnThoiGianDongBo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->string('thoi_gian_dong_bo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->dropColumn('thoi_gian_dong_bo');
        });
    }
}
