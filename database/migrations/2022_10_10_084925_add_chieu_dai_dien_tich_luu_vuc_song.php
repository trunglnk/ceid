<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChieuDaiDienTichLuuVucSong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('luu_vuc_songs', function (Blueprint $table){
            $table->double('chieu_dai')->nullable();
            $table->double('dien_tich')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('luu_vuc_songs', function (Blueprint $table){
            $table->dropColumn('chieu_dai');
            $table->dropColumn('dien_tich');
        });
    }
}
