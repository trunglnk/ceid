<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnTableNhomHanhViViPhams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function (Blueprint $table) { 
            $table->dropColumn(['cap_bo_phe_duyet', 'cap_dia_phuong_phe_duyet']);            
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
            $table->boolean('cap_bo_phe_duyet')->default(false);
            $table->boolean('cap_dia_phuong_phe_duyet')->default(false);
        });        
    }
}
