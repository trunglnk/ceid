<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeSoQuyetDinhThongBao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thong_bao_quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->string('so_thong_bao_quyet_dinh')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thong_bao_quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->unsignedInteger('so_thong_bao_quyet_dinh')->change();
        });
    }
}
