<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaMucLoaiKhuCongNghiep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loai_khu_cong_nghieps', function (Blueprint $table) {
            $table->string('ma_muc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loai_khu_cong_nghieps', function (Blueprint $table) {
            $table->dropColumn('ma_muc');
        });
    }
}
