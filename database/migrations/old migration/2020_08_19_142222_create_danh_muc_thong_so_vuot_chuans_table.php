<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDanhMucThongSoVuotChuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('danh_muc_thong_so_vuot_chuans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten')->nullable();
            $table->string('ky_hieu_hoa_hoc')->nullable();
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
        Schema::dropIfExists('danh_muc_thong_so_vuot_chuans');
    }
}
