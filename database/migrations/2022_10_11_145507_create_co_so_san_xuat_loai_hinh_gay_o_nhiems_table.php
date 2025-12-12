<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoSoSanXuatLoaiHinhGayONhiemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_so_san_xuat_loai_hinh_gay_o_nhiems', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            
            $table->integer('co_so_id');
            $table->foreign('co_so_id')
            ->references('id')
            ->on('co_so_san_xuats')
            ->onDelete('cascade');

            $table->integer('loai_hinh_o_nhiem_id');
            $table->foreign('loai_hinh_o_nhiem_id')
            ->references('id')
            ->on('loai_hinh_oi_nhiems')
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
        Schema::dropIfExists('co_so_san_xuat_loai_hinh_gay_o_nhiems');
    }
}
