<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleleTableChiTietCoSoThanhTra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('chi_tiet_co_so_thanh_tra');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('chi_tiet_co_so_thanh_tra', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quyet_dinh_thanh_tra_id');
            $table->foreign('quyet_dinh_thanh_tra_id')->references('id')->on('quyet_dinh_thanh_tras');
            $table->unsignedInteger('co_so_san_xuat_id');
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
            $table->timestamps();
            
        });
    }
}
