<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXuPhatChinhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xu_phat_chinhs', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('so_tien_xu_phat_chinh',18,0)->nullable();                
            $table->text('noi_dung_vi_pham')->nullable();
            $table->unsignedInteger('quyet_dinh_xu_phat_id');
            $table->foreign('quyet_dinh_xu_phat_id')->references('id')->on('quyet_dinh_xu_phats');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xu_phat_chinhs');
    }
}
