<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCacaseDeleteSomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chi_tiet_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->dropForeign(['khac_phuc_hau_qua_id']);
    
            $table->foreign('khac_phuc_hau_qua_id')
                ->references('id')
                ->on('ket_qua_khac_phuc_hau_quas')
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
        Schema::table('chi_tiet_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->dropForeign(['khac_phuc_hau_qua_id']);
    
            $table->foreign('khac_phuc_hau_qua_id')
                ->references('id')
                ->on('ket_qua_khac_phuc_hau_quas');
        });
    }
}
