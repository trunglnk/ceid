<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChiTietCongSuatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_cong_suats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('co_so_san_xuat_id')->nullable();
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
            $table->unsignedInteger('don_vi_id')->nullable();
            $table->foreign('don_vi_id')->references('id')->on('chuyen_doi_don_vis');
            $table->string('loai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chi_tiet_cong_suats');
    }
}
