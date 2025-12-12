<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColDiaDiemLayMauToChiTietHanhViViPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chi_tiet_vi_pham_xa_thais', function (Blueprint $table) {
            $table->text('dia_diem_lay_mau')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chi_tiet_vi_pham_xa_thais', function (Blueprint $table) {
            $table->dropColumn('dia_diem_lay_mau');
        });
    }
}
