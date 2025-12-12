<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteCoSoSanXuatIdKhacPhucHauQuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('khac_phuc_hau_quas', function (Blueprint $table) {
            $table->dropColumn('co_so_san_xuat_id');                               
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('khac_phuc_hau_quas', function (Blueprint $table) {
            $table->unsignedInteger('co_so_san_xuat_id');
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');    
        });
    }
}
