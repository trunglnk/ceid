<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChiTietKhacPhucHauQuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('khac_phuc_hau_qua_id');
            $table->foreign('khac_phuc_hau_qua_id')->references('id')->on('khac_phuc_hau_quas');
            $table->unsignedInteger('bien_phap_khac_phuc_id');
            $table->foreign('bien_phap_khac_phuc_id')->references('id')->on('cac_bien_phap_khac_phuc_hau_quas');
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
        Schema::dropIfExists('chi_tiet_khac_phuc_hau_quas');
    }
}
