<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTinhThanhQuyetDinhThanhLapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tinh_thanh_quyet_dinh_thanh_laps', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('tinh_thanh_id');
            $table->integer('quyet_dinh_thanh_lap_id');

            $table->foreign('tinh_thanh_id')
            ->references('id')
            ->on('tinh_thanhs')
            ->onDelete('cascade');

            $table->foreign('quyet_dinh_thanh_lap_id')
            ->references('id')
            ->on('quyet_dinh_thanh_tras')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tinh_thanh_quyet_dinh_thanh_laps');
    }
}
