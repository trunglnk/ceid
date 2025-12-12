<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLoaiHinhCoSo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loai_hinh_co_sos', function (Blueprint $table) {
            $table->string('ma')->nullable();
            $table->string('van_ban_phap_luat')->default('04/2012/TT-BTNMT');
        });
        Schema::table('loai_hinh_co_sos', function (Blueprint $table) {
            $table->string('van_ban_phap_luat')->default('27/2018/QĐ-TTg')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loai_hinh_co_sos', function (Blueprint $table) {
            $table->dropColumn('ma');
            $table->dropColumn('van_ban_phap_luat');
        });
    }
}
