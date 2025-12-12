<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeThongSoNuocThaiVuotChuanKetQuaThanhTraNuocThaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_nuoc_thais', function($table)
        {
            $table->string('thong_so_nuoc_thai_vuot_chuan', 5000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ket_qua_thanh_tra_nuoc_thais', function($table)
        {
            $table->string('thong_so_nuoc_thai_vuot_chuan', 255)->change();
        });
    }
}
