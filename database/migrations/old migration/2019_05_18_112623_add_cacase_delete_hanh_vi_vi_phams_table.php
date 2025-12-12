<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCacaseDeleteHanhViViPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hanh_vi_vi_phams', function (Blueprint $table) {
            $table->dropForeign(['xu_phat_chinh_id']);
    
            $table->foreign('xu_phat_chinh_id')
                ->references('id')
                ->on('xu_phat_chinhs')
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
        Schema::table('hanh_vi_vi_phams', function (Blueprint $table) {
            $table->dropForeign(['xu_phat_chinh_id']);
    
            $table->foreign('xu_phat_chinh_id')
                ->references('id')
                ->on('xu_phat_chinhs');
        });
    }
}
