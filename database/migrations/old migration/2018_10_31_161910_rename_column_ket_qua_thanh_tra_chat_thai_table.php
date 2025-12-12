<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnKetQuaThanhTraChatThaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $table->renameColumn('tu_xu_ly_ctrsh','tu_xu_ly');
        });

        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->renameColumn('tu_xu_ly_ctrsh','tu_xu_ly');
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
            $table->renameColumn('tu_xu_ly','tu_xu_ly_ctrsh');
        });

        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->renameColumn('tu_xu_ly','tu_xu_ly_ctrsh');
        });
    }
}
