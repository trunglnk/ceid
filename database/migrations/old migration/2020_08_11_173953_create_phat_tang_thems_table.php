<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhatTangThemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phat_tang_thems', function (Blueprint $table) {
            $table->increments('id');
            $table->float('phan_tram_tang_them')->nullable();
            $table->float('tu_gia_tri')->nullable();
            $table->float('den_gia_tri')->nullable();
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
        Schema::dropIfExists('phat_tang_thems');
    }
}
