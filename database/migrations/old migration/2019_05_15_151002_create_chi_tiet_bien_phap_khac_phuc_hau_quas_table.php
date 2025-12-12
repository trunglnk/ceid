<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChiTietBienPhapKhacPhucHauQuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_bien_phap_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bien_phap_khac_phuc_hau_qua_id')->nullable();
            $table->foreign('bien_phap_khac_phuc_hau_qua_id')->references('id')->on('bien_phap_khac_phuc_hau_quas');
            $table->unsignedInteger('cac_bien_phap_khac_phuc_hau_qua_id')->nullable();
            $table->foreign('cac_bien_phap_khac_phuc_hau_qua_id')->references('id')->on('cac_bien_phap_khac_phuc_hau_quas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chi_tiet_bien_phap_khac_phuc_hau_quas');
    }
}
