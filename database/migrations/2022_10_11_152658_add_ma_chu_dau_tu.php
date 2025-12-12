<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaChuDauTu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chu_dau_tus', function (Blueprint $table){
            $table->string('ma')->nullable();
            $table->string('loai_du_lieu')->default('du_lieu_goc');
            $table->string('trang_thai_dong_bo')->default('chua_dong_bo');
        });
        Schema::table('co_so_san_xuats', function (Blueprint $table){
            $table->string('ma_dinh_danh')->nullable();
            $table->string('loai_du_lieu')->default('du_lieu_goc');
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
        Schema::table('chu_dau_tus', function (Blueprint $table){
            $table->dropColumn('ma');
            $table->dropColumn('loai_du_lieu');
            $table->dropColumn('trang_thai_dong_bo');
        });
        Schema::table('co_so_san_xuats', function (Blueprint $table){
            $table->string('ma_dinh_danh')->nullable();
            $table->string('loai_du_lieu')->default('du_lieu_goc');
            $table->string('trang_thai_dong_bo')->default('chua_dong_bo');
        });
    }
}
