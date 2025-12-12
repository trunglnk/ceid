<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangKyTuVietTatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bang_ky_tu_viet_tats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('chu_viet_tat', 500)->nullable();            
            $table->text('nguyen_nghia')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bang_ky_tu_viet_tats');
    }
}
