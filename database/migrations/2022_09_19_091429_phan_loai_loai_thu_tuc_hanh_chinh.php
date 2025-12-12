<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PhanLoaiLoaiThuTucHanhChinh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loai_thu_tuc_hanh_chinhs', function (Blueprint $table) {
            $table->string('phan_loai')->default('khac');
            $table->string('ma_muc_mtqg')->nullable();
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loai_thu_tuc_hanh_chinhs', function (Blueprint $table) {
            $table->dropColumn('phan_loai');
            $table->dropColumn('ma_muc_mtqg');
        });
    }
}
