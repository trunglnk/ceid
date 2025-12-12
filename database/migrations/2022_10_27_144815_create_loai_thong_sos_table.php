<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoaiThongSosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loai_thong_sos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->string('ma')->nullable();
            $table->string('trang_thai_dong_bo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loai_thong_sos');
    }
}
