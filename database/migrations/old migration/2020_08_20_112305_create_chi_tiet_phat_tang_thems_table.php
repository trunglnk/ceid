<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChiTietPhatTangThemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_phat_tang_thems', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nhom_hanh_vi_vi_pham_id')->nullable();
            $table->integer('phat_tang_them_id')->nullable();
            $table->integer('don_vi_id')->nullable();
            $table->integer('thong_so_id')->nullable();
            $table->float('ket_qua')->nullable();
            $table->float('so_lan_vuot')->nullable();
            $table->string('type')->nullable();

            $table->foreign('nhom_hanh_vi_vi_pham_id')->nullable()
                ->references('id')
                ->on('nhom_hanh_vi_vi_phams')
                ->onDelete('cascade');

            $table->foreign('phat_tang_them_id')->nullable()
                ->references('id')
                ->on('phat_tang_thems')
                ->onDelete('cascade');
            $table->foreign('thong_so_id')->nullable()
                ->references('id')
                ->on('danh_muc_thong_so_vuot_chuans')
                ->onDelete('cascade');
            $table->foreign('don_vi_id')->nullable()
                ->references('id')
                ->on('chuyen_doi_don_vis')
                ->onDelete('cascade');
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
        Schema::dropIfExists('chi_tiet_phat_tang_thems');
    }
}
