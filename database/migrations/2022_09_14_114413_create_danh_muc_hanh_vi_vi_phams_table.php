<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDanhMucHanhViViPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('danh_muc_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ma_hanh_vi')->nullable();
            $table->text('nhom_hanh_vi')->nullable();
            $table->text('ten_hanh_vi')->nullable();
            $table->string('phat_nho_nhat')->nullable();
            $table->string('phat_lon_nhat')->nullable();
            $table->text('dieu_luat')->nullable();
            $table->text('xu_phat_bo_xung')->nullable();
            $table->text('bien_phap_khac_phuc_hau_qua')->nulable();
            $table->string('phap_ly')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('danh_muc_hanh_vi_vi_phams');
    }
}
