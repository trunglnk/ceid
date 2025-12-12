<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhieuThuNghiemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phieu_thu_nghiems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten_khach_hang')->nullable();
            $table->string('dia_diem_lay_mau')->nullable();
            $table->string('dia_chi')->nullable()->nullable();
            $table->string('vi_tri_quan_trac')->nullable();
            $table->double('kinh_do', 19, 15)->nullable();
            $table->double('vi_do', 19, 15)->nullable();
            $table->string('dac_diem')->nullable();
            $table->date('ngay_lay_mau')->nullable();
            $table->string('nguoi_lay_mau')->nullable();
            $table->string('thoi_tiet')->nullable();
            $table->date('ngay_phan_tich_start')->nullable();
            $table->date('ngay_phan_tich_end')->nullable();
            $table->string('nguoi_phan_tich')->nullable();
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
        Schema::dropIfExists('phieu_thu_nghiems');
    }
}
