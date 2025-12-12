<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnKetLuanThanhTraIdToQuyetDinhXuPhatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->text('ghi_chu')->nullable();
            $table->unsignedInteger('ket_qua_thanh_tra_id')->nullable();
            $table->foreign('ket_qua_thanh_tra_id')->references('id')->on('ket_qua_thanh_tras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_id');
            $table->dropColumn('ghi_chu');
        });
    }
}
