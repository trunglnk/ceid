<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKhuCongNghiepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khu_cong_nghieps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ma')->unique();
            $table->string('ten');
            $table->string('dia_chi')->nullable();
            $table->string('xa_phuong')->nullable();
            $table->unsignedInteger('quan_huyen_id')->nullable();
            $table->foreign('quan_huyen_id')->references('id')->on('quan_huyens');
            $table->unsignedInteger('tinh_thanh_id')->nullable();
            $table->foreign('tinh_thanh_id')->references('id')->on('tinh_thanhs');
            $table->boolean('inactive')->default(false);      
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
        Schema::dropIfExists('khu_cong_nghieps');
    }
}
