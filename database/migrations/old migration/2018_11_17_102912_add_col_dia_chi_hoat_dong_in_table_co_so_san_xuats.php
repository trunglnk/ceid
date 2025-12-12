<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColDiaChiHoatDongInTableCoSoSanXuats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->text('dia_chi_hoat_dong')->nullable();
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
            $table->dropColumn('dia_chi_hoat_dong');
        });
    }
}
