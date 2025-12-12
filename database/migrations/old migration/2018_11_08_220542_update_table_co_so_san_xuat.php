<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableCoSoSanXuat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->unsignedInteger('co_quan_cap_giay_chung_nhan_dau_tu')->nullable();
            $table->foreign('co_quan_cap_giay_chung_nhan_dau_tu')->references('id')->on('co_quan_to_chucs');
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
            $table->dropColumn('co_quan_cap_giay_chung_nhan_dau_tu');
        });
    }
}
