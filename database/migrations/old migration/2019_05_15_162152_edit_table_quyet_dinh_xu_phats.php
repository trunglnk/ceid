<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTableQuyetDinhXuPhats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {            
            $table->dropColumn('so_tien_da_nop_phat');            
            $table->dropColumn('co_so_san_xuat_id');            
            $table->dropColumn('so_quyet_dinh_sua_doi');            
            $table->dropColumn('ngay_sua_doi_quyet_dinh');            
            $table->dropForeign('quyet_dinh_xu_phats_ket_qua_thanh_tra_chung_id_foreign'); 
            $table->dropColumn('ket_qua_thanh_tra_chung_id');          
            $table->dropColumn('inactive');  
            $table->dropForeign('quyet_dinh_xu_phats_hanh_vi_vi_pham_foreign');     
            $table->dropColumn('hanh_vi_vi_pham');          
            $table->dropColumn('so_ket_luan');          
        });          
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->decimal('so_tien_da_nop_phat',18,0); 
            $table->unsignedInteger('co_so_san_xuat_id');
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');  
            $table->string('so_quyet_dinh_sua_doi')->nullable(); 
            $table->date('ngay_sua_doi_quyet_dinh')->nullable(); 
            $table->unsignedInteger('ket_qua_thanh_tra_chung_id')->nullable();
            $table->foreign('ket_qua_thanh_tra_chung_id')->references('id')->on('ket_qua_thanh_tra_chungs'); 
            $table->boolean('inactive')->default(false);            
            $table->string('hanh_vi_vi_pham')->nullable();              
            $table->string('so_ket_luan')->nullable();              
        });  
    }
}
