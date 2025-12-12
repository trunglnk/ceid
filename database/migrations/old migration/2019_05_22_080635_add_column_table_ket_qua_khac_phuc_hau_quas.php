<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTableKetQuaKhacPhucHauQuas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->text('noi_dung_da_khac_phuc')->nullable();         
            $table->text('noi_dung_chua_khac_phuc')->nullable();         
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
            $table->dropColumn('noi_dung_chua_khac_phuc');       
            $table->dropColumn('noi_dung_da_khac_phuc');       
        });
    }
}
