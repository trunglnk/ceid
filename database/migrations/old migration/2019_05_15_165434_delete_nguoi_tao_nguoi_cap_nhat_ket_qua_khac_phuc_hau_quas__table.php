<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteNguoiTaoNguoiCapNhatKetQuaKhacPhucHauQuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_khac_phuc_hau_quas', function (Blueprint $table) {            
            $table->dropForeign('khac_phuc_hau_quas_nguoi_cap_nhat_foreign');
            $table->dropColumn('nguoi_cap_nhat');                                  
            $table->dropForeign('khac_phuc_hau_quas_nguoi_tao_foreign');
            $table->dropColumn('nguoi_tao');          
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
            $table->unsignedInteger('nguoi_cap_nhat');
            $table->foreign('nguoi_cap_nhat')->references('id')->on('users');
            $table->unsignedInteger('nguoi_tao');
            $table->foreign('nguoi_tao')->references('id')->on('users');      
        });          
    }
}
