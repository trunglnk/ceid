<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteCoQuanRaQuyetDinhTableQuyetDinhThanhTras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quyet_dinh_thanh_tras', function (Blueprint $table) {                        
            $table->dropColumn('co_quan_thong_bao');                                                                                                            
        });                 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quyet_dinh_thanh_tras', function (Blueprint $table) {                             
            $table->unsignedInteger('co_quan_thong_bao_id')->nullable();
            $table->foreign('co_quan_thong_bao_id')->references('id')->on('co_quan_to_chucs');                                
        });
    }
}
