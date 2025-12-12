<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChuDauTusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chu_dau_tus', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('ten');
            $table->text('dia_chi')->nullable();
            $table->text('so_nha_chi_tiet')->nullable();
            $table->unsignedInteger('quan_huyen_id')->nullable();
            $table->foreign('quan_huyen_id')->references('id')->on('quan_huyens');
            $table->unsignedInteger('tinh_thanh_id')->nullable();
            $table->foreign('tinh_thanh_id')->references('id')->on('tinh_thanhs');
            $table->string('ma_so_qlctnh')->nullable();
            $table->string('nguoi_dai_dien')->nullable();
            $table->string('so_dien_thoai')->nullable();
            $table->string('email')->nullable();
            $table->string('fax')->nullable();
            $table->string('dang_ky_kinh_doanh')->nullable();
            $table->string('so_giay_chung_nhan_dang_ky_kinh_doanh')->nullable();
            $table->string('co_quan_cap_giay_chung_nhan_dang_ky_kinh_doanh')->nullable();
            $table->date('ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh')->nullable();
            $table->integer('lan_cap_giay_chung_nhan_dang_ky_kinh_doanh')->min(1)->nullable();
            $table->date('ngay_cap_lan_dau_giay_chung_nhan_dang_ky_kinh_doanh')->nullable();
            $table->string('chung_nhan_dau_tu')->nullable();
            $table->string('so_giay_chung_nhan_dau_tu')->nullable();
            $table->string('noi_cap_giay_chung_nhan_dau_tu')->nullable();
            $table->date('ngay_cap_giay_chung_nhan_dau_tu')->nullable();
            $table->integer('lan_cap_giay_chung_nhan_dau_tu')->min(1)->nullable();
            $table->date('ngay_cap_lan_dau_giay_chung_nhan_dau_tu')->nullable();

            $table->text('ghi_chu')->nullable();
            $table->text('xa_phuong')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chu_dau_tus');
    }
}
