<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSoHieuBaoCaoNgayBanHanhBaoCaoKhacPhucViPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('khac_phuc_hau_quas', function (Blueprint $table) {
            $table->string('so_hieu_bao_cao')->nullable();
            $table->datetime('ngay_ban_hanh_bao_cao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('khac_phuc_hau_quas', function (Blueprint $table) {
            $table->dropColumn('so_hieu_bao_cao');
            $table->dropColumn('ngay_ban_hanh_bao_cao');
        });
    }
}
