<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColHanhViViPham extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->dropColumn('hanh_vi_vi_pham');
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
            $table->string('hanh_vi_vi_pham')->nullable();
        });
    }
}
