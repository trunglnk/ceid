<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuyetDinhXuPhatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('so_quyet_dinh');
            $table->string('co_quan_quyet_dinh_xu_phat');
            $table->date('thoi_gian_ban_hanh')->nullable();     
            $table->string('hanh_vi_vi_pham')->nullable();  
            $table->decimal('so_tien_xu_phat',18,0)->nullable();   
            $table->decimal('so_tien_da_nop_phat',18,0)->nullable(); 
            $table->string('bien_lai_nop_phat')->nullable(); 
            $table->date('thoi_gian_nop_phat')->nullable(); 
            $table->unsignedInteger('co_so_san_xuat_id')->nullable(); 
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');  
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
        Schema::dropIfExists('quyet_dinh_xu_phats');
    }
}
