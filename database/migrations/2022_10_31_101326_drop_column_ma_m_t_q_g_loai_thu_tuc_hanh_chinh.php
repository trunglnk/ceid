<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnMaMTQGLoaiThuTucHanhChinh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loai_thu_tuc_hanh_chinhs', function (Blueprint $table) {
            $table->dropColumn('ma_muc_mtqg');
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
            $table->string('ma_muc_mtqg')->nullable();
        });
    }
}
