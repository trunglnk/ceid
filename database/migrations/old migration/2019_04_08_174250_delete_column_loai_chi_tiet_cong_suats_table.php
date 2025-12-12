<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnLoaiChiTietCongSuatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chi_tiet_cong_suats', function (Blueprint $table) {
            $table->dropColumn('loai');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {      
        Schema::table('chi_tiet_cong_suats', function (Blueprint $table) {
            $table->string('loai')->nullable();          
        });
    }
}
