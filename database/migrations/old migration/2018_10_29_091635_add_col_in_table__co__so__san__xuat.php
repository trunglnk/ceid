<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColInTableCoSoSanXuat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->unsignedInteger('luu_vuc_song_id')->nullable();
            $table->foreign('luu_vuc_song_id')->references('id')->on('vung_kinh_te_trong_diems');
            $table->unsignedInteger('vung_kinh_te_trong_diem_id')->nullable();
            $table->foreign('vung_kinh_te_trong_diem_id')->references('id')->on('vung_kinh_te_trong_diems');
            $table->unsignedInteger('mien_id')->nullable();
            $table->foreign('mien_id')->references('id')->on('miens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->dropColumn('luu_vuc_song_id');
            $table->dropColumn('vung_kinh_te_trong_diem_id');
            $table->dropColumn('mien_id');
        });
    }
}
