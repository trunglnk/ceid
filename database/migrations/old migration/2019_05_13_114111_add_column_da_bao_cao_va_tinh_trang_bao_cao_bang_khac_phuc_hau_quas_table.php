<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDaBaoCaoVaTinhTrangBaoCaoBangKhacPhucHauQuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('khac_phuc_hau_quas', function (Blueprint $table) {
            $table->string('tinh_trang_bao_cao')->nullable();
            $table->boolean('da_bao_cao')->default(false);
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
            $table->dropColumn('tinh_trang_bao_cao');
            $table->dropColumn('da_bao_cao');
        });
    }
}
