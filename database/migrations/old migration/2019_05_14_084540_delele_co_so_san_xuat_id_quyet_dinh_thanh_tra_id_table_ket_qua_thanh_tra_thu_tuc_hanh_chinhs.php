<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleleCoSoSanXuatIdQuyetDinhThanhTraIdTableKetQuaThanhTraThuTucHanhChinhs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_thu_tuc_hanh_chinhs', function (Blueprint $table) {
            $table->dropColumn('co_so_san_xuat_id');                               
            $table->dropColumn('quyet_dinh_thanh_tra_id');                               
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ket_qua_thanh_tra_thu_tuc_hanh_chinhs', function (Blueprint $table) {
            $table->unsignedInteger('co_so_san_xuat_id');
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');    
            $table->unsignedInteger('quyet_dinh_thanh_tra_id');
            $table->foreign('quyet_dinh_thanh_tra_id')->references('id')->on('quyet_dinh_thanh_tras');    
        });
    }
}
