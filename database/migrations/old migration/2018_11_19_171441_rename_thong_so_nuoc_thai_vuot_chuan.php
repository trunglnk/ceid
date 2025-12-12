<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameThongSoNuocThaiVuotChuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
            $table->renameColumn('thong_so_nuoc_thai_vuot_chuan','thong_so_khi_thai_vuot_chuan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
            $table->renameColumn('thong_so_khi_thai_vuot_chuan','thong_so_nuoc_thai_vuot_chuan')->nullable();
        });
    }
}
