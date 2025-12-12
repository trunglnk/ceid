<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCacaseDeleteChiTietXuPhatBoSungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chi_tiet_xu_phat_bo_sungs', function (Blueprint $table) {
            $table->dropForeign(['xu_phat_bo_sung_id']);
    
            $table->foreign('xu_phat_bo_sung_id')
                ->references('id')
                ->on('xu_phat_bo_sungs')
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
        Schema::table('chi_tiet_xu_phat_bo_sungs', function (Blueprint $table) {
            $table->dropForeign(['xu_phat_bo_sung_id']);
    
            $table->foreign('xu_phat_bo_sung_id')
                ->references('id')
                ->on('xu_phat_bo_sungs');
        });
    }
}
