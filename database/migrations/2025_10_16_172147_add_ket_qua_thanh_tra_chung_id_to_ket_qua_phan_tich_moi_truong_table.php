<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKetQuaThanhTraChungIdToKetQuaPhanTichMoiTruongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_phan_tich_moi_truong', function (Blueprint $table) {
            if (!Schema::hasColumn('ket_qua_phan_tich_moi_truong', 'ket_qua_thanh_tra_chung_id')) {
                $table->unsignedBigInteger('ket_qua_thanh_tra_chung_id')->nullable()->after('id');
            }

            $table->foreign('ket_qua_thanh_tra_chung_id')
                ->references('id')
                ->on('ket_qua_thanh_tra_chungs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ket_qua_phan_tich_moi_truong', function (Blueprint $table) {
            $table->dropForeign(['ket_qua_thanh_tra_chung_id']);
            $table->dropColumn('ket_qua_thanh_tra_chung_id');
        });
    }
}
