<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnsKetQuaThanhTrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tras', function (Blueprint $table) {
            $table->dropColumn(['luu_luong_nuoc_thai', 
                'cong_suat_he_thong_xu_ly_nuoc_thai', 
                'nguon_tiep_nhan_nuoc_thai', 
                'thong_so_nuoc_thai_vuot_chuan',
                'luu_luong_khi_thai', 
                'cong_suat_cua_he_thong_xu_ly_khi_thai',
                'nguon_tiep_nhan_khi_thai',
                'thong_so_khi_thai_vuot_chuan',
                'khoi_luong_phat_sinh_ctrsh',
                'thanh_phan_chinh_ctrsh',
                'tu_xu_ly_ctrsh',
                'khoi_luong_phat_sinh_ctrcntt',
                'thanh_phan_chinh_ctrcntt',
                'tu_xu_ly_ctrcntt',
                'khoi_luong_phat_sinh_thuc_te_ctnh',
                'khoi_luong_phat_sinh_theo_so_dang_ky_ctnh',
                'thanh_phan_ctnh',
                'tu_xu_ly_ctnh'
            ]);            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
