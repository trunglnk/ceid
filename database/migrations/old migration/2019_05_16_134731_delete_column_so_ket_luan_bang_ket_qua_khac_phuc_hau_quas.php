<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnSoKetLuanBangKetQuaKhacPhucHauQuas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_khac_phuc_hau_quas', function (Blueprint $table) {                        
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
        Schema::table('ket_qua_khac_phuc_hau_quas', function (Blueprint $table) {                             
            $table->string('so_ket_luan')->nullable();                                  
        });
    }
}
