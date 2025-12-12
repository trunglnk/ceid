<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCacaseDeleteChiTietBienPhapKhacPhucHauQuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chi_tiet_bien_phap_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->dropForeign(['bien_phap_khac_phuc_hau_qua_id']);
    
            $table->foreign('bien_phap_khac_phuc_hau_qua_id')
                ->references('id')
                ->on('bien_phap_khac_phuc_hau_quas')
                ->onDelete('cascade');
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chi_tiet_bien_phap_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->dropForeign(['bien_phap_khac_phuc_hau_qua_id']);
    
            $table->foreign('bien_phap_khac_phuc_hau_qua_id')
                ->references('id')
                ->on('bien_phap_khac_phuc_hau_quas');
        });
    }
}
