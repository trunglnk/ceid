<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTableCoSoSanXuatsChiTietCongSuats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chi_tiet_cong_suats', function (Blueprint $table) {
            $table->unsignedInteger('loai_hinh_id')->nullable();
            $table->foreign('loai_hinh_id')->references('id')->on('loai_hinh_co_sos');
        });
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->dropColumn('loai_hinh_id');
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
            $table->unsignedInteger('loai_hinh_id')->nullable();
            $table->foreign('loai_hinh_id')->references('id')->on('loai_hinh_co_sos');
        });
        Schema::table('chi_tiet_cong_suats', function (Blueprint $table) {
            $table->dropColumn('loai_hinh_id');
        });
    }
}
