<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoSoSanXuatLoaiNganhNghesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_so_san_xuat_loai_nganh_nghes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('co_so_id');
            $table->foreign('co_so_id')
            ->references('id')
            ->on('co_so_san_xuats')
            ->onDelete('cascade');

            $table->integer('loai_nganh_nghe_id');
            $table->foreign('loai_nganh_nghe_id')
            ->references('id')
            ->on('loai_hinh_co_sos')
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
        Schema::dropIfExists('co_so_san_xuat_loai_nganh_nghes');
    }
}
