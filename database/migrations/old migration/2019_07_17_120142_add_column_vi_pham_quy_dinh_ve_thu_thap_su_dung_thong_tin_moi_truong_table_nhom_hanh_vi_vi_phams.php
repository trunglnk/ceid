<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnViPhamQuyDinhVeThuThapSuDungThongTinMoiTruongTableNhomHanhViViPhams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->boolean('vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong')->default(false);        
            $table->boolean('vi_pham_cac_quy_dinh_bao_ve_moi_truong')->default(false);        
            $table->boolean('co_hanh_vi_can_tro_ve_bvmt')->default(false);        
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->dropColumn([
                'vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong',
                'vi_pham_cac_quy_dinh_bao_ve_moi_truong',
                'co_hanh_vi_can_tro_ve_bvmt',
            ]);       
        });
    }
}
