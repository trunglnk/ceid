<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColOrderInTableCoQuanToChucs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_quan_to_chucs', function (Blueprint $table) {
            $table->unsignedInteger('order')->nullable();

        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_quan_to_chucs', function (Blueprint $table) {
            $table->dropColumn('order');

        });       
    }
}
