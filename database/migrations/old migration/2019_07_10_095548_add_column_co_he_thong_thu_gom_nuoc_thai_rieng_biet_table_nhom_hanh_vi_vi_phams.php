<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCoHeThongThuGomNuocThaiRiengBietTableNhomHanhViViPhams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->boolean('co_he_thong_thu_gom_nuoc_thai_rieng_biet')->default(false);        
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->dropColumn([
                'co_he_thong_thu_gom_nuoc_thai_rieng_biet'
            ]);       
        });
    }
}
