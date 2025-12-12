<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTableHanhViViPhams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hanh_vi_vi_phams', function (Blueprint $table) {            
            $table->dropColumn('quyet_dinh_xu_phat_id');                                  
            $table->unsignedInteger('xu_phat_chinh_id');
            $table->foreign('xu_phat_chinh_id')->references('id')->on('xu_phat_chinhs');         
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
            $table->unsignedInteger('quyet_dinh_xu_phat_id');
            $table->foreign('quyet_dinh_xu_phat_id')->references('id')->on('quyet_dinh_xu_phats');
            $table->dropForeign('hanh_vi_vi_phams_xu_phat_chinh_id_foreign');  
            $table->dropColumn('xu_phat_chinh_id');            
        });  
    }
}
