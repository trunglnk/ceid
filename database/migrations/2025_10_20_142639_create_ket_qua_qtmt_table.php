<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetQuaQtmtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_qtmt', function (Blueprint $table) {
            $table->increments('id');
            // ======= Thông tin nhận dạng bản ghi gốc =======
            $table->string('ma_dinh_danh')->nullable()->comment('Mã định danh kết quả QTMT');
            $table->string('ma_tram')->nullable()->comment('Mã định danh trạm / vị trí QTMT');
            $table->string('ten_tram')->nullable()->comment('Tên trạm / vị trí quan trắc');

            // ======= Phân loại / metadata =======
            $table->string('loai_hinh_qtmt')->nullable()->comment('Loại hình quan trắc môi trường (VD: Nước dưới đất)');
            $table->string('loai_so_lieu_qtmt')->nullable()->comment('Loại số liệu QTMT (VD: TB1Y)');
            $table->integer('nam_quan_trac')->nullable()->comment('Năm quan trắc');
            $table->integer('thang_quan_trac')->nullable()->comment('Tháng quan trắc');
            $table->string('trang_thai')->nullable()->comment('Trạng thái dữ liệu');
            $table->string('nguon_tham_chieu')->nullable()->comment('Nguồn tham chiếu / file gốc từ CEID');
            $table->timestamp('modified_at')->nullable()->comment('Thời điểm cập nhật dữ liệu quốc gia');

            // ======= Thông tin thông số đo =======
            $table->string('thong_so_ma')->nullable()->comment('Mã thông số môi trường');
            $table->string('thong_so_ten')->nullable()->comment('Tên thông số môi trường');
            $table->string('quy_chuan_ma')->nullable()->comment('Mã quy chuẩn môi trường');
            $table->string('quy_chuan_ten')->nullable()->comment('Tên quy chuẩn môi trường');
            $table->string('don_vi_ma')->nullable()->comment('Mã đơn vị đo');
            $table->string('don_vi_ten')->nullable()->comment('Tên đơn vị đo');

            // ======= Giá trị đo =======
            $table->string('gia_tri_quan_trac')->nullable()->comment('Giá trị quan trắc (text để chứa cả KPH, ND, ... )');
            $table->double('gia_tri_gioi_han_max')->nullable()->comment('Giá trị giới hạn tối đa (nếu có)');

            $table->timestamps();

            // ======= Index & Unique =======
            $table->unique(['ma_dinh_danh', 'thong_so_ma'], 'uq_kq_qtmt_ma_dinh_danh_thong_so');
            $table->index(['ma_tram', 'nam_quan_trac', 'thang_quan_trac'], 'idx_kq_qtmt_tram_thoigian');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ket_qua_qtmt');
    }
}
