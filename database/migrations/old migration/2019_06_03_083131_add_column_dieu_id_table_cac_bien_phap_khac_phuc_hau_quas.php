<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDieuIdTableCacBienPhapKhacPhucHauQuas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cac_bien_phap_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->unsignedInteger('dieu_id')->nullable(); 
            $table->foreign('dieu_id')
                ->references('id')
                ->on('dieus')
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
        Schema::table('cac_bien_phap_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->dropColumn('dieu_id');       
        });
    }
}
