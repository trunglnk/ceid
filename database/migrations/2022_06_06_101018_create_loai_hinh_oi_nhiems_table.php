<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoaiHinhOiNhiemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loai_hinh_oi_nhiems', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ma')->nullable();
            $table->text('ten');
            $table->boolean('inactive')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loai_hinh_oi_nhiems');
    }
}
