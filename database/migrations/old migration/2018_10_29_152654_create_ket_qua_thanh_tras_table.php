<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetQuaThanhTrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_thanh_tras', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('co_so_san_xuat_id');
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
            $table->unsignedInteger('quyet_dinh_thanh_tra_id');
            $table->foreign('quyet_dinh_thanh_tra_id')->references('id')->on('quyet_dinh_thanh_tras');
            $table->double('luu_luong_nuoc_thai')->nullable();
            $table->double('cong_suat_he_thong_xu_ly_nuoc_thai')->nullable();
            $table->string('nguon_tiep_nhan_nuoc_thai')->nullable();
            $table->double('thong_so_nuoc_thai_vuot_chuan')->nullable();
            $table->double('luu_luong_khi_thai')->nullable();
            $table->double('cong_suat_cua_he_thong_xu_ly_khi_thai')->nullable();
            $table->string('nguon_tiep_nhan_khi_thai')->nullable();
            $table->double('thong_so_khi_thai_vuot_chuan')->nullable();
            $table->double('khoi_luong_phat_sinh_ctrsh')->nullable();
            $table->string('thanh_phan_chinh_ctrsh')->nullable();
            $table->boolean('tu_xu_ly_ctrsh')->default(true);
            $table->double('khoi_luong_phat_sinh_ctrcntt')->nullable();
            $table->string('thanh_phan_chinh_ctrcntt')->nullable();
            $table->boolean('tu_xu_ly_ctrcntt')->default(false);
            $table->double('khoi_luong_phat_sinh_thuc_te_ctnh')->nullable();
            $table->double('khoi_luong_phat_sinh_theo_so_dang_ky_ctnh')->nullable();
            $table->string('thanh_phan_ctnh')->nullable();
            $table->boolean('tu_xu_ly_ctnh')->default(false);
            $table->date('ngay_thanh_tra')->nullable();
            $table->unsignedInteger('nguoi_tao')->nullable();
            $table->foreign('nguoi_tao')->references('id')->on('users');
            $table->unsignedInteger('nguoi_cap_nhat')->nullable();
            $table->foreign('nguoi_cap_nhat')->references('id')->on('users');
            $table->string('mo_ta')->nullable();
            $table->boolean('inactive')->default(false);
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
        Schema::dropIfExists('ket_qua_thanh_tras');
    }
}
