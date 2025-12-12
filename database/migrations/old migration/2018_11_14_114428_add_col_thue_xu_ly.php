<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColThueXuLy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $table->boolean('thue_xu_ly')->default(false);

        });    
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->boolean('thue_xu_ly')->default(false);

        });  
        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->boolean('thue_xu_ly')->default(false);

        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $table->dropColumn('thue_xu_ly');

        });    
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->dropColumn('thue_xu_ly');

        });    
        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->dropColumn('thue_xu_ly');

        });    
    }
}
