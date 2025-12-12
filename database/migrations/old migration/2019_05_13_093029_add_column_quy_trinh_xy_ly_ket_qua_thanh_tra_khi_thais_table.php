<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnQuyTrinhXyLyKetQuaThanhTraKhiThaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
            $table->text('quy_trinh_xu_ly')->nullable();
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
            $table->dropColumn('quy_trinh_xu_ly');
        });
    }
}
