<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCoSoSanXuatIdKetQuaThanhTraChungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_chungs', function (Blueprint $table) {
            $table->unsignedInteger('co_so_san_xuat_id')->nullable();
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ket_qua_thanh_tra_chungs', function (Blueprint $table) {
            $table->dropColumn('co_so_san_xuat_id');  
        });
    }
}
