<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNhomHanhViViPham extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->boolean('khi_thai_vuot')->default(false);
            $table->unsignedInteger('khi_thai_muc_vi_pham_id')->nullable();
            $table->foreign('khi_thai_muc_vi_pham_id')
                ->references('id')
                ->on('mucs');
            $table->unsignedInteger('khi_thai_khoan_vi_pham_id')->nullable();
            $table->foreign('khi_thai_khoan_vi_pham_id')
                ->references('id')
                ->on('khoans');
            $table->longText('khi_thai_thong_so_vuot_chuan')->nullable();
            $table->unsignedInteger('nuoc_thai_muc_vi_pham_id')->nullable();
            $table->foreign('nuoc_thai_muc_vi_pham_id')
                ->references('id')
                ->on('mucs');
            $table->unsignedInteger('nuoc_thai_khoan_vi_pham_id')->nullable();
            $table->foreign('nuoc_thai_khoan_vi_pham_id')
                ->references('id')
                ->on('khoans');
            $table->longText('nuoc_thai_thong_so_vuot_chuan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nhom_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->dropColumn('khi_thai_muc_vi_pham_id');
            $table->dropColumn('khi_thai_khoan_vi_pham_id');
            $table->dropColumn('khi_thai_thong_so_vuot_chuan');
            $table->dropColumn('khi_thai_vuot');
            $table->dropColumn('nuoc_thai_muc_vi_pham_id');
            $table->dropColumn('nuoc_thai_khoan_vi_pham_id');
            $table->dropColumn('nuoc_thai_thong_so_vuot_chuan');
        });
    }
}
