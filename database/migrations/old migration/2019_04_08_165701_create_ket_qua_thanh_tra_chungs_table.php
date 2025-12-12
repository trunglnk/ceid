<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetQuaThanhTraChungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_thanh_tra_chungs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ket_qua_thanh_tra_id');
            $table->foreign('ket_qua_thanh_tra_id')->references('id')->on('ket_qua_thanh_tras');
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
            $table->unsignedInteger('co_quan_cap_giay_chung_nhan_dau_tu')->nullable();
            $table->foreign('co_quan_cap_giay_chung_nhan_dau_tu')->references('id')->on('co_quan_to_chucs');
            $table->text('nguyen_lieu_chinh_su_dung')->nullable();
            $table->text('nhien_lieu_chinh_su_dung')->nullable();
            $table->text('hoa_chat_su_dung')->nullable();
            $table->text('nguon_nuoc_su_dung')->nullable();
            $table->unsignedInteger('vung_kinh_te_trong_diem_id')->nullable();
            $table->foreign('vung_kinh_te_trong_diem_id')->references('id')->on('vung_kinh_te_trong_diems');
            $table->unsignedInteger('mien_id')->nullable();
            $table->foreign('mien_id')->references('id')->on('miens');
            $table->unsignedInteger('luu_vuc_song_id')->nullable();
            $table->foreign('luu_vuc_song_id')->references('id')->on('luu_vuc_songs'); 
            $table->date('ngay_cap_giay_chung_nhan_kinh_doanh')->nullable();
            $table->string('so_giay_chung_nhan_kinh_doanh')->references('id')->nullable();
            $table->unsignedInteger('co_quan_cap_giay_chung_nhan_kinh_doanh')->nullable();
            $table->foreign('co_quan_cap_giay_chung_nhan_kinh_doanh')->references('id')->on('co_quan_to_chucs');  
            $table->double('luong_nuoc_su_dung')->nullable(); 
            $table->text('dia_chi_hoat_dong')->nullable();                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ket_qua_thanh_tra_chungs');
    }
}
