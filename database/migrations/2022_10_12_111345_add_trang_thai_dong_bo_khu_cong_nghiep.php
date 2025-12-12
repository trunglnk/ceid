<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrangThaiDongBoKhuCongNghiep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loai_khu_cong_nghieps', function (Blueprint $table){
            $table->string('trang_thai_dong_bo')->default('chua_dong_bo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loai_khu_cong_nghieps', function (Blueprint $table){
            $table->dropColumn('trang_thai_dong_bo');
        });
    }
}
