<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTableCoSoSanXuatAddCol1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->string('luong_nuoc_su_dung')->nullable();
            $table->dropColumn('so_giay_chung_nhan_kinh_doanh')->nullable();
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
            $table->dropColumn('luong_nuoc_su_dung');
            $table->string('so_giay_chung_nhan_kinh_doanh');
        });
    }
}
