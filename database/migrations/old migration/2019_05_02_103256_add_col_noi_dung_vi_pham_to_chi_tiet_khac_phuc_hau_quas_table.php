<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColNoiDungViPhamToChiTietKhacPhucHauQuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('chi_tiet_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->text('noi_dung_vi_pham')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chi_tiet_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->dropColumn('noi_dung_vi_pham');
        });
    }
}