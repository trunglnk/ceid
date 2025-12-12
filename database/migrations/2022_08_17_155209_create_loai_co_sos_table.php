<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoaiCoSosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loai_co_sos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('ten_co_so') -> nullable();
        });
        // Schema::table('co_so_san_xuats', function (Blueprint $table){
        //     $table->datetime('ngay_tao')->nullable();
        //     $table->datetime('ngay_cap_nhat')->nullable();
        //     $table->integer('loai_co_so_id')->nullable();
        //     $table->integer('loai_hinh_oi_nhiem_id')->nullable();
        //     $table->integer('loai_nganh_kinh_te_id')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loai_co_sos');
        // Schema::table('co_so_san_xuats', function (Blueprint $table){
        //     $table->dropColumn('ngay_tao');
        //     $table->dropColumn('ngay_cap_nhat');
        //     $table->dropColumn('loai_co_so_id');
        //     $table->dropColumn('loai_hinh_oi_nhiem_id');
        //     $table->dropColumn('loai_nganh_kinh_te_id');
        // });
    }
}
