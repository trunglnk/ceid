<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnGsmtThucHienTableNhomHanhViViPhams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->boolean('gsmt_thuc_hien')->default(false);        
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
                'gsmt_thuc_hien'
            ]);       
        });
    }
}
