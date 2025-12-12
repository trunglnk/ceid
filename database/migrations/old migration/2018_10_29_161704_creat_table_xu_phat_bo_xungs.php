<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatTableXuPhatBoXungs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xu_phat_bo_xungs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('co_so_san_xuat_id');
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
            $table->unsignedInteger('quyet_dinh_xu_phat_id');
            $table->foreign('quyet_dinh_xu_phat_id')->references('id')->on('quyet_dinh_xu_phats');
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
        Schema::dropIfExists('xu_phat_bo_xungs'); 
    }
}
