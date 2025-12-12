<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDinhChiTableXuPhatBoXungs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xu_phat_bo_sungs', function (Blueprint $table) {
            $table->boolean('dinh_chi')->default(false);              
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xu_phat_bo_sungs', function (Blueprint $table) {
            $table->dropColumn('dinh_chi');       
        });
    }
}
