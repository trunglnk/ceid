<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDieuIdTableLoaiXuPhatBoSung extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loai_xu_phat_bo_sungs', function (Blueprint $table) {
            $table->unsignedInteger('dieu_id')->nullable(); 
            $table->foreign('dieu_id')
                ->references('id')
                ->on('dieus')
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
        Schema::table('loai_xu_phat_bo_sungs', function (Blueprint $table) {
            $table->dropColumn('dieu_id');       
        });
    }
}
