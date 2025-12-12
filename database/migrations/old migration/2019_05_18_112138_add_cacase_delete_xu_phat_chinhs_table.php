<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCacaseDeleteXuPhatChinhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xu_phat_chinhs', function (Blueprint $table) {
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
        Schema::table('xu_phat_chinhs', function (Blueprint $table) {
            $table->dropForeign(['quyet_dinh_xu_phat_id']);
    
            $table->foreign('quyet_dinh_xu_phat_id')
                ->references('id')
                ->on('quyet_dinh_xu_phats');
        });
    }
}
