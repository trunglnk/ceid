<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnNoiDungChuaThucHienTableKetQuaThanhTras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tras', function (Blueprint $table) {
            $table->text('noi_dung_chua_thuc_hien')->nullable();         
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ket_qua_thanh_tras', function (Blueprint $table) {
            $table->dropColumn('noi_dung_chua_thuc_hien');       
        });
    }
}
