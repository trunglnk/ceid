<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoSoSanXuatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_so_san_xuats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten');
            $table->double('kinh_do',19,15)->default(0);
            $table->double('vi_do',19,15)->default(0);
            $table->string('dia_chi_lien_he',1000)->nullable();
            $table->unsignedInteger('khu_cong_nghiep_id')->nullable();
            $table->foreign('khu_cong_nghiep_id')->references('id')->on('khu_cong_nghieps');
            $table->string('xa_phuong',1000)->nullable();
            $table->unsignedInteger('quan_huyen_id')->nullable();
            $table->foreign('quan_huyen_id')->references('id')->on('quan_huyens');
            $table->unsignedInteger('tinh_thanh_id')->nullable();
            $table->foreign('tinh_thanh_id')->references('id')->on('tinh_thanhs');
            $table->double('dien_tich',8,2)->nullable();
            $table->unsignedInteger('so_nguoi_lao_dong')->nullable();
            $table->string('cong_suat_thiet_ke',1000)->nullable();
            $table->string('cong_suat_hoat_dong',1000)->nullable();
            $table->string('so_giay_chung_nhan_dau_tu',1000)->nullable();
            $table->date('ngay_cap_giay_chung_nhan_dau_tu')->nullable();
            $table->string('co_quan_cap_giay_chung_nhan_dau_tu',1000)->nullable();
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('co_so_san_xuats');
    }
}
