<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCacaseDeleteBienPhapKhacPhucHauQuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bien_phap_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->dropForeign(['quyet_dinh_xu_phat_id']);
    
            $table->foreign('quyet_dinh_xu_phat_id')
                ->references('id')
                ->on('quyet_dinh_xu_phats')
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
        Schema::table('bien_phap_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->dropForeign(['quyet_dinh_xu_phat_id']);
    
            $table->foreign('quyet_dinh_xu_phat_id')
                ->references('id')
                ->on('quyet_dinh_xu_phats');
        });
    }
}
