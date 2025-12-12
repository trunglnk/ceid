<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBienPhapKhacPhucHauQuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bien_phap_khac_phuc_hau_quas', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('so_tien_chung_cau',18,0)->nullable();                
            $table->text('noi_dung_bien_phap_khac_phuc_hau_qua')->nullable();
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
        Schema::dropIfExists('bien_phap_khac_phuc_hau_quas');
    }
}
