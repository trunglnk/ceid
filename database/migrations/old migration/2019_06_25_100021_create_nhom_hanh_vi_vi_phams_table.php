<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNhomHanhViViPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhom_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->boolean('cap_bo_phe_duyet')->default(false);
            $table->boolean('cap_dia_phuong_phe_duyet')->default(false);
            $table->boolean('vi_pham')->default(false);
            $table->unsignedInteger('ket_qua_thanh_tra_id')->unique();
            $table->foreign('ket_qua_thanh_tra_id')
                ->references('id')
                ->on('ket_qua_thanh_tras')
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
        Schema::dropIfExists('nhom_hanh_vi_vi_phams');
    }
}
