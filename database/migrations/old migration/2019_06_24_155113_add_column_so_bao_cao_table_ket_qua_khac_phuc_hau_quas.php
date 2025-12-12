<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSoBaoCaoTableKetQuaKhacPhucHauQuas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->string('so_bao_cao')->nullable();              
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ket_qua_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->dropColumn('so_bao_cao');       
        });
    }
}
