<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChiTietViPhamXaThaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_vi_pham_xa_thais', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('nhom_hanh_vi_vi_pham_id')->nullable();
            $table->foreign('nhom_hanh_vi_vi_pham_id')
            ->references('id')
            ->on('nhom_hanh_vi_vi_phams')
            ->onDelete('cascade');

            $table->unsignedInteger('muc_vi_pham_id')->nullable();
            $table->foreign('muc_vi_pham_id')
                ->references('id')
                ->on('mucs');

            $table->unsignedInteger('khoan_vi_pham_id')->nullable();
            $table->foreign('khoan_vi_pham_id')
                ->references('id')
                ->on('khoans');

            $table->integer('thong_so_id')->nullable();
            $table->foreign('thong_so_id')
            ->references('id')
            ->on('danh_muc_thong_so_vuot_chuans')
            ->onDelete('cascade');

            $table->integer('don_vi_id')->nullable();
            $table->foreign('don_vi_id')
            ->references('id')
            ->on('chuyen_doi_don_vis')
            ->onDelete('cascade');

            $table->integer('co_so_san_xuat_id')->nullable();
            $table->foreign('co_so_san_xuat_id')
            ->references('id')
            ->on('co_so_san_xuats')
            ->onDelete('cascade');

            $table->float('ket_qua')->nullable();
            $table->float('so_lan_vuot')->nullable();
            $table->string('type')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chi_tiet_vi_pham_xa_thais');
    }
}
