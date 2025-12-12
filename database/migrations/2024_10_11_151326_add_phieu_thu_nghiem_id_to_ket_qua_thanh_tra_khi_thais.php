<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhieuThuNghiemIdToKetQuaThanhTraKhiThais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
            $table->bigInteger('phieu_thu_nghiem_id')->nullable();
            $table->foreign('phieu_thu_nghiem_id')
                ->references('id')->on('phieu_thu_nghiems')
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
        Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
            // Xóa khóa ngoại trước khi xóa cột
            $table->dropForeign(['phieu_thu_nghiem_id']);
            $table->dropColumn('phieu_thu_nghiem_id'); // Xóa cột
        });
    }
}
