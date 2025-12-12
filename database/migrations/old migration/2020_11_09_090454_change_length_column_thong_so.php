<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLengthColumnThongSo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
            $table->string('thong_so_khi_thai_vuot_chuan', 6000)->change();
        });
        Schema::table('ket_qua_thanh_tra_nuoc_thais', function (Blueprint $table) {
            $table->string('thong_so_nuoc_thai_vuot_chuan', 6000)->change();
        });

        Schema::table('ket_qua_thanh_tras', function (Blueprint $table) {
            $table->string('thanh_phan_chinh_ctrsh',6000)->nullable();
            $table->string('thanh_phan_chinh_ctrcntt',6000)->nullable();
            $table->string('thanh_phan_ctnh',6000)->nullable();
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
    }
}
