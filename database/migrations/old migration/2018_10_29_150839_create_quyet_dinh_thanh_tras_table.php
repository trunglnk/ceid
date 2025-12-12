<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuyetDinhThanhTrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('co_quan_quyet_dinh');
            $table->foreign('co_quan_quyet_dinh')->references('id')->on('co_quan_to_chucs');
            $table->date('ngay_ra_quyet_dinh')->nullable();
            $table->string('so_quyet_dinh_thong_bao')->nullable();
            $table->unsignedInteger('co_quan_thong_bao');
            $table->foreign('co_quan_thong_bao')->references('id')->on('co_quan_to_chucs');
            $table->date('thoi_gian_thong_bao')->nullable();
            $table->unsignedInteger('hinh_thuc_thanh_tra');
            $table->foreign('hinh_thuc_thanh_tra')->references('id')->on('che_do_thanh_tras');
            $table->unsignedInteger('co_quan_thuc_hien');
            $table->foreign('co_quan_thuc_hien')->references('id')->on('co_quan_to_chucs');
            $table->unsignedInteger('nguoi_tao');
            $table->foreign('nguoi_tao')->references('id')->on('users');
            $table->unsignedInteger('nguoi_cap_nhat');
            $table->foreign('nguoi_cap_nhat')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quyet_dinh_thanh_tras');
    }
}
