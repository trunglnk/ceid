<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTableKetQuaKhacPhucHauQuas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('khac_phuc_hau_quas', function (Blueprint $table) {            
            $table->dropColumn('ket_qua_thanh_tra_chung_id');                                  
            $table->unsignedInteger('ket_qua_thanh_tra_id');
            $table->foreign('ket_qua_thanh_tra_id')->references('id')->on('ket_qua_thanh_tras');         
        });
        Schema::rename('khac_phuc_hau_quas', 'ket_qua_khac_phuc_hau_quas');          
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('khac_phuc_hau_quas', function (Blueprint $table) {            
            $table->unsignedInteger('ket_qua_thanh_tra_chung_id');
            $table->foreign('ket_qua_thanh_tra_chung_id')->references('id')->on('ket_qua_thanh_tra_chungs');
            $table->dropForeign('khac_phuc_hau_quas_ket_qua_thanh_tra_id_foreign');  
            $table->dropColumn('ket_qua_thanh_tra_id');            
        });  
        Schema::rename('ket_qua_khac_phuc_hau_quas', 'khac_phuc_hau_quas'); 
    }
}
