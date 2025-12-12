<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnCongSuatCoSoSanXuatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->dropColumn(['cong_suat_thiet_ke', 'cong_suat_hoat_dong']);                               
        });
        Schema::table('ket_qua_thanh_tra_chungs', function (Blueprint $table) {
            $table->dropColumn(['cong_suat_thiet_ke', 'cong_suat_hoat_dong']);                               
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->string('cong_suat_thiet_ke',1000)->nullable();
            $table->string('cong_suat_hoat_dong',1000)->nullable();         
        });
        Schema::table('ket_qua_thanh_tra_chungs', function (Blueprint $table) {
            $table->string('cong_suat_thiet_ke',1000)->nullable();
            $table->string('cong_suat_hoat_dong',1000)->nullable();         
        });
    }
}
