<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiaDiemCoSoSanXuatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dia_diem_co_so_san_xuats', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('co_so_san_xuat_id')->nullable();
            $table->foreign('co_so_san_xuat_id')
                ->references('id')
                ->on('co_so_san_xuats')
                ->onDelete('cascade');

            $table->unsignedInteger('phuong_xa_id')->nullable();
            $table->foreign('phuong_xa_id')
                ->references('id')
                ->on('phuong_xas')
                ->onDelete('cascade');

            $table->unsignedInteger('quan_huyen_id')->nullable();
            $table->foreign('quan_huyen_id')
                ->references('id')
                ->on('quan_huyens')
                ->onDelete('cascade');

            $table->unsignedInteger('tinh_thanh_id')->nullable();
            $table->foreign('tinh_thanh_id')
                ->references('id')
                ->on('tinh_thanhs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dia_diem_co_so_san_xuats');
    }
}
