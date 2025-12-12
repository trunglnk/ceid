<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCoQuanDonVi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_quan_to_chucs', function (Blueprint $table) {
            $table->string('ma_dinh_danh')->nullable();
            $table->integer('tinh_thanh_id')->nullable();
            $table->foreign('tinh_thanh_id')
            ->references('id')
            ->on('tinh_thanhs')
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
        Schema::table('co_quan_to_chucs', function (Blueprint $table) {
            $table->dropColumn('ma_dinh_danh');
            $table->dropColumn('tinh_thanh_id');
        });
    }
}
