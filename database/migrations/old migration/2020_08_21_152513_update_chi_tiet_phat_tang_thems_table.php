<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateChiTietPhatTangThemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chi_tiet_phat_tang_thems', function (Blueprint $table) {
            $table->dropColumn('nhom_hanh_vi_vi_pham_id');
            $table->dropColumn('type');
            $table->integer('chi_tiet_vi_pham_xa_thai_id')->nullable();

            $table->foreign('chi_tiet_vi_pham_xa_thai_id')
                ->references('id')
                ->on('chi_tiet_vi_pham_xa_thais')
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
        Schema::table('chi_tiet_phat_tang_thems', function (Blueprint $table) {
            $table->integer('nhom_hanh_vi_vi_pham_id')->nullable();
            $table->string('type')->nullable();
            $table->dropColumn('chi_tiet_vi_pham_xa_thai_id');

            $table->foreign('nhom_hanh_vi_vi_pham_id')
                ->references('id')
                ->on('nhom_hanh_vi_vi_phams')
                ->onDelete('cascade');
        });
    }
}
