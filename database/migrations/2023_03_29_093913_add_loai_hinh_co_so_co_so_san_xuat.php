<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLoaiHinhCoSoCoSoSanXuat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->string('loai_khu_cong_nghiep')->nullable();
            $table->foreign('loai_khu_cong_nghiep')
            ->references('ma')
            ->on('loai_khu_cong_nghieps')
            ->onDelete('set null');
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
            $table->dropColumn('loai_khu_cong_nghiep');
        });
    }
}
