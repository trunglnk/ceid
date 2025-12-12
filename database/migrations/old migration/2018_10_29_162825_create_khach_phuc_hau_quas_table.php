<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKhachPhucHauQuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khac_phuc_hau_quas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('so_quyet_dinh');
            $table->unsignedInteger('co_so_san_xuat_id');
            $table->foreign('co_so_san_xuat_id')->references('id')->on('co_so_san_xuats');
            $table->decimal('so_tien',18,0)->nullable();
            $table->date('thoi_gian_ban_hanh')->nullable();
            $table->date('thoi_gian_nop_phat')->nullable();
            $table->boolean('da_nop_phat')->default(false);
            $table->unsignedInteger('nguoi_tao');
            $table->foreign('nguoi_tao')->references('id')->on('users');
            $table->unsignedInteger('nguoi_cap_nhat');
            $table->foreign('nguoi_cap_nhat')->references('id')->on('users');
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
        Schema::dropIfExists('khac_phuc_hau_quas');
    }
}
