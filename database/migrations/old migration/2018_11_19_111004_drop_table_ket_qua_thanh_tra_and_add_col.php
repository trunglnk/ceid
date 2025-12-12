<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTableKetQuaThanhTraAndAddCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ket_qua_thanh_tras');
        
        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_id');
            $table->string('so_ket_luan')->nullable();
            $table->unsignedInteger('quyet_dinh_thanh_tra_id')->nullable();
            $table->foreign('quyet_dinh_thanh_tra_id')->references('id')->on('quyet_dinh_thanh_tras');
            $table->date('ngay_ket_luan_thanh_tra')->nullable();
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_id');
            $table->string('so_ket_luan')->nullable();
            $table->unsignedInteger('quyet_dinh_thanh_tra_id')->nullable();
            $table->foreign('quyet_dinh_thanh_tra_id')->references('id')->on('quyet_dinh_thanh_tras');
            $table->date('ngay_ket_luan_thanh_tra')->nullable();
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_id');
            $table->string('so_ket_luan')->nullable();
            $table->unsignedInteger('quyet_dinh_thanh_tra_id')->nullable();
            $table->foreign('quyet_dinh_thanh_tra_id')->references('id')->on('quyet_dinh_thanh_tras');
            $table->date('ngay_ket_luan_thanh_tra')->nullable();
        });
        Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_id');
            $table->string('so_ket_luan')->nullable();
            $table->unsignedInteger('quyet_dinh_thanh_tra_id')->nullable();
            $table->foreign('quyet_dinh_thanh_tra_id')->references('id')->on('quyet_dinh_thanh_tras');
            $table->date('ngay_ket_luan_thanh_tra')->nullable();
        });
        Schema::table('ket_qua_thanh_tra_nuoc_thais', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_id');
            $table->string('so_ket_luan')->nullable();
            $table->unsignedInteger('quyet_dinh_thanh_tra_id')->nullable();
            $table->foreign('quyet_dinh_thanh_tra_id')->references('id')->on('quyet_dinh_thanh_tras');
            $table->date('ngay_ket_luan_thanh_tra')->nullable();
        });
        Schema::table('ket_qua_thanh_tra_thu_tuc_hanh_chinhs', function (Blueprint $table) {
            $table->dropColumn('ket_qua_thanh_tra_id');
            $table->string('so_ket_luan')->nullable();
            $table->unsignedInteger('quyet_dinh_thanh_tra_id')->nullable();
            $table->foreign('quyet_dinh_thanh_tra_id')->references('id')->on('quyet_dinh_thanh_tras');
            $table->date('ngay_ket_luan_thanh_tra')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('ket_qua_thanh_tra_chat_thai_nguy_hais', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_id');
            $table->dropColumn('so_ket_luan');
            $table->dropColumn('quyet_dinh_thanh_tra_id');
            $table->dropColumn('ngay_ket_luan_thanh_tra');
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_cntts', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_id');
            $table->dropColumn('so_ket_luan');
            $table->dropColumn('quyet_dinh_thanh_tra_id');
            $table->dropColumn('ngay_ket_luan_thanh_tra');
        });
        Schema::table('ket_qua_thanh_tra_chat_thai_ran_sinh_hoats', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_id');
            $table->dropColumn('so_ket_luan');
            $table->dropColumn('quyet_dinh_thanh_tra_id');
            $table->dropColumn('ngay_ket_luan_thanh_tra');
        });
        Schema::table('ket_qua_thanh_tra_khi_thais', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_id');
            $table->dropColumn('so_ket_luan');
            $table->dropColumn('quyet_dinh_thanh_tra_id');
            $table->dropColumn('ngay_ket_luan_thanh_tra');
        });
        Schema::table('ket_qua_thanh_tra_nuoc_thais', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_id');
            $table->dropColumn('so_ket_luan');
            $table->dropColumn('quyet_dinh_thanh_tra_id');
            $table->dropColumn('ngay_ket_luan_thanh_tra');
        });
        Schema::table('ket_qua_thanh_tra_thu_tuc_hanh_chinhs', function (Blueprint $table) {
            $table->unsignedInteger('ket_qua_thanh_tra_id');
            $table->dropColumn('so_ket_luan');
            $table->dropColumn('quyet_dinh_thanh_tra_id');
            $table->dropColumn('ngay_ket_luan_thanh_tra');
        });
    }
}
