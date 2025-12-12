<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnNhomHanhViViPhamTabls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->boolean('dtmdakhbvmt_thuc_hien_khong_dung_noi_dung')->default(false);              
            $table->boolean('dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung')->default(false);                            
            $table->boolean('co_ket_hoach_quan_ly_moi_truong')->default(false);              
            $table->boolean('khong_xay_lap')->default(false);              
            $table->boolean('xay_lap_khong_dung')->default(false);              
            $table->boolean('van_hanh_khong_dung_khong_thuong_xuyen')->default(false);              
            $table->boolean('gsmt_khong_thuc_hien')->default(false);              
            $table->boolean('gsmt_thuc_hien_khong_dung_khong_du')->default(false);              
            $table->boolean('thuc_hien_sai_giay_xac_nhan')->default(false);              
            $table->boolean('nuoc_thai_vuot')->default(false);              
            $table->double('nuoc_thai_vuot_qcvn_tu')->default(0);              
            $table->double('nuoc_thai_vuot_qcvn_toi')->default(0);              
            $table->boolean('co_bien_phap_xu_ly_khi_thai')->default(false);              
            $table->double('khi_thai_vuot_qcvn_tu')->default(0);              
            $table->double('khi_thai_vuot_qcvn_toi')->default(0);

            $table->boolean('ctrsh_co_thu_gom')->default(false);              
            $table->boolean('ctrsh_co_phan_loai')->default(false);              
            $table->boolean('ctrsh_co_luu_tru')->default(false);              
            $table->boolean('ctrsh_co_chuyen_giao')->default(false);

            $table->boolean('ctrcn_co_thu_gom')->default(false);              
            $table->boolean('ctrcn_co_phan_loai')->default(false);              
            $table->boolean('ctrcn_co_luu_tru')->default(false);              
            $table->boolean('ctrcn_co_chuyen_giao')->default(false);  
            
            $table->boolean('ctrnh_vi_pham_chung_tu')->default(false);              
            $table->boolean('ctrnh_co_thu_gom')->default(false);              
            $table->boolean('ctrnh_co_phan_loai')->default(false);              
            $table->boolean('ctrnh_co_luu_tru')->default(false);              
            $table->boolean('ctrnh_co_de_lan')->default(false);              
            $table->boolean('ctrnh_co_chuyen_giao')->default(false);  
            $table->boolean('ctrnh_co_chon_lap')->default(false);  
            $table->boolean('ctrnh_co_do_thai')->default(false);  
            $table->boolean('ctrnh_co_giay_phep')->default(false); 

            $table->boolean('nhom_hanh_vi_khac')->default(false);  
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
                'dtmdakhbvmt_thuc_hien_khong_dung_noi_dung',              
                'dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung',              
                'co_ket_hoach_quan_ly_moi_truong',              
                'khong_xay_lap',              
                'xay_lap_khong_dung',              
                'van_hanh_khong_dung_khong_thuong_xuyen',              
                'gsmt_khong_thuc_hien',              
                'gsmt_thuc_hien_khong_dung_khong_du',              
                'thuc_hien_sai_giay_xac_nhan',              
                'nuoc_thai_vuot',              
                'nuoc_thai_vuot_qcvn_tu',              
                'nuoc_thai_vuot_qcvn_toi',              
                'co_bien_phap_xu_ly_khi_thai',              
                'khi_thai_vuot_qcvn_tu',              
                'khi_thai_vuot_qcvn_toi',              
                'ctrsh_co_thu_gom',              
                'ctrsh_co_phan_loai',              
                'ctrsh_co_luu_tru',              
                'ctrsh_co_chuyen_giao',
                'ctrcn_co_thu_gom',              
                'ctrcn_co_phan_loai',              
                'ctrcn_co_luu_tru',              
                'ctrcn_co_chuyen_giao', 
                'ctrnh_vi_pham_chung_tu',              
                'ctrnh_co_thu_gom',              
                'ctrnh_co_phan_loai',              
                'ctrnh_co_luu_tru',              
                'ctrnh_co_de_lan',              
                'ctrnh_co_chuyen_giao',              
                'ctrnh_co_chon_lap',              
                'ctrnh_co_do_thai',              
                'ctrnh_co_giay_phep',              
                'nhom_hanh_vi_khac'
            ]);       
        });
    }
}
