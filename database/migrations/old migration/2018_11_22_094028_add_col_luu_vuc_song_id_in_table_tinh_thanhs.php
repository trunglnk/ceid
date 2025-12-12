<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColLuuVucSongIdInTableTinhThanhs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      
        Schema::table('tinh_thanhs', function (Blueprint $table) {
            $table->unsignedInteger('luu_vuc_song_id')->nullable();
            $table->foreign('luu_vuc_song_id')->references('id')->on('luu_vuc_songs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tinh_thanhs', function (Blueprint $table) {
            $table->dropColumn('luu_vuc_song_id');
        });
    }
}
