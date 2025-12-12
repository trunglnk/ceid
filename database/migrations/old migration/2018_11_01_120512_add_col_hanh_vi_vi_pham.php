<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColHanhViViPham extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->unsignedInteger('hanh_vi_vi_pham')->nullable();
            $table->foreign('hanh_vi_vi_pham')->references('id')->on('co_quan_to_chucs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->dropColumn('hanh_vi_vi_pham');
        });
    }
}
