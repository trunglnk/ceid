<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoSoSanXuatQuocGiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_so_san_xuat_quoc_gias', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->json('cap_phep_phe_lieu')->nullable();
            $table->json('day_truyen_phan_khu')->nullable();
            $table->json('chu_dau_tu')->nullable();
            $table->json('dia_chi')->nullable();
            $table->json('loai_hinh_co_so')->nullable();
            $table->json('loai_hinh_o_nhiem')->nullable();
            $table->json('loai_nganh_nghe_kinh_te')->nullable();
            $table->string('ma_dinh_danh')->nullable();
            $table->string('ten')->nullable();
            $table->json('van_ban_gpmt')->nullable();
            $table->json('van_ban_dtm')->nullable();
            $table->json('tinh_trang_hoat_dong')->nullable();
            $table->string('id_csdlqg')->nullable();
            $table->json('ton_tai_tap_trung')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_so_san_xuat_quoc_gias');
    }
}
