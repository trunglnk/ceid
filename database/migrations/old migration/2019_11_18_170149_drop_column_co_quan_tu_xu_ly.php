<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnCoQuanTuXuLy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $table->dropColumn('co_quan_tu_xu_ly');
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->dropColumn('co_quan_tu_xu_ly');
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->dropColumn('co_quan_tu_xu_ly');
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
            $table->text('co_quan_thue_xu_ly')->nullable();
            $table->text('co_quan_tu_xu_ly')->nullable();
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->text('co_quan_thue_xu_ly')->nullable();
            $table->text('co_quan_tu_xu_ly')->nullable();
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->text('co_quan_thue_xu_ly')->nullable();
            $table->text('co_quan_tu_xu_ly')->nullable();
        });
    }
}
