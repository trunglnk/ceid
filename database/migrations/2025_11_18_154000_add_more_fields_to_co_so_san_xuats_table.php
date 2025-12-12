<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsToCoSoSanXuatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->decimal('ty_le_lap_day', 5, 2)->nullable()->after('ngay_ket_luan');
            $table->decimal('tong_dien_tich', 15, 2)->nullable()->after('ty_le_lap_day');

            $table->unsignedBigInteger('chuyen_doi_don_vi_id')->nullable()->after('tong_dien_tich');

            $table->decimal('dien_tich_dat_cn', 15, 2)->nullable()->after('chuyen_doi_don_vi_id');
            $table->decimal('dien_tich_dat_cho_thue', 15, 2)->nullable()->after('dien_tich_dat_cn');
            $table->decimal('dien_tich_dat_cay_xanh', 15, 2)->nullable()->after('dien_tich_dat_cho_thue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->dropColumn([
                'ty_le_lap_day',
                'tong_dien_tich',
                'chuyen_doi_don_vi_id',
                'dien_tich_dat_cn',
                'dien_tich_dat_cho_thue',
                'dien_tich_dat_cay_xanh',
            ]);
        });
    }
}
