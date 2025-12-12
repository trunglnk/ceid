<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSoKetLuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thong_bao_quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->string('so_ket_luan')->nullable();
        });
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->string('so_ket_luan')->nullable();
        });
        Schema::table('khac_phuc_hau_quas', function (Blueprint $table) {
            $table->string('so_ket_luan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thong_bao_quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->dropColumn('so_ket_luan');
        });
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->dropColumn('so_ket_luan');
        });
        Schema::table('khac_phuc_hau_quas', function (Blueprint $table) {
            $table->dropColumn('so_ket_luan');
        });
    }
}
