<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiemTramQtmtTable extends Migration
{
    public function up(): void
    {
        Schema::create('diem_tram_qtmt', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ma_dinh_danh')->unique();
            $table->string('ten_goi')->nullable();

            // Địa chỉ
            $table->string('dia_chi_chi_tiet')->nullable();
            $table->string('phuong_xa_ma')->nullable();
            $table->string('phuong_xa_ten')->nullable();
            $table->string('quan_huyen_ma')->nullable();
            $table->string('quan_huyen_ten')->nullable();
            $table->string('tinh_thanh_ma')->nullable();
            $table->string('tinh_thanh_ten')->nullable();

            // Vị trí địa lý
            $table->decimal('kinh_do', 20, 10)->nullable();
            $table->decimal('vi_do', 20, 10)->nullable();

            // Kênh sông & lưu vực
            $table->string('kenh_song_ma')->nullable();
            $table->string('kenh_song_ten')->nullable();
            $table->string('luu_vuc_song_ma')->nullable();
            $table->string('luu_vuc_song_ten')->nullable();

            // Loại điểm
            $table->string('loai_diem_qtmt_ma')->nullable();
            $table->string('loai_diem_qtmt_ten')->nullable();

            // Trạng thái dữ liệu
            $table->string('trang_thai_ma')->nullable();
            $table->string('trang_thai_ten')->nullable();

            // Ngày cập nhật từ nguồn
            $table->timestamp('modified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diem_tram_qtmt');
    }
};