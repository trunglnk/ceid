<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaDinhDanhChuDauTu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chu_dau_tus', function (Blueprint $table) {
            $table->string('ma_dinh_danh')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chu_dau_tus', function (Blueprint $table) {
            $table->dropColumn('ma_dinh_danh');
        });
    }
}
