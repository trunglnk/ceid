<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLoaiHinhQuanTracToKetQuaThanhTraKhiThaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
            $table->string('loai_hinh_quan_trac')->nullable()->after('phieu_thu_nghiem_id');
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
            $table->dropColumn('loai_hinh_quan_trac');
        });
    }
}
