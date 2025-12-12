<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTableChiTietKhacPhucHauQuas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('chi_tiet_khac_phuc_hau_quas');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('chi_tiet_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('khac_phuc_hau_qua_id');
            $table->foreign('khac_phuc_hau_qua_id')
                ->references('id')
                ->on('ket_qua_khac_phuc_hau_quas')
                ->onDelete('cascade');
            $table->unsignedInteger('bien_phap_khac_phuc_id');
            $table->foreign('bien_phap_khac_phuc_id')->references('id')->on('cac_bien_phap_khac_phuc_hau_quas');
            $table->text('noi_dung_vi_pham')->nullable();
            $table->timestamps();
        });
    }
}
