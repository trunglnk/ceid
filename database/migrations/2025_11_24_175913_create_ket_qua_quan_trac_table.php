<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetQuaQuanTracTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_quan_trac', function (Blueprint $table) {
            $table->bigIncrements('id');
            // Thông tin mẫu
            $table->string('dia_diem')->nullable()->comment('Địa điểm lấy mẫu');
            $table->string('vi_tri')->nullable()->comment('Vị trí lấy mẫu');
            $table->string('loai_mau')->nullable()->comment('Loại mẫu: Nước thải, Nước mặt, Khí thải,...');
            $table->decimal('kinh_do', 10, 6)->nullable()->comment('Kinh độ (Longitude)');
            $table->decimal('vi_do', 10, 6)->nullable()->comment('Vĩ độ (Latitude)');

            // Thông số phân tích
            $table->string('thong_so')->nullable()->comment('Thông số phân tích (pH, TDS, ... )');
            $table->string('don_vi_tinh')->nullable()->comment('Đơn vị tính (mg/L, ... )');
            $table->string('phuong_phap_phan_tich')->nullable()->comment('Phương pháp phân tích (TCVN...)');
            $table->string('ket_qua')->nullable()->comment('Kết quả đo đạc');
            $table->string('gia_tri_gioi_han')->nullable()->comment('Giá trị giới hạn theo QCVN');
            $table->string('so_lan_vuot')->nullable()->comment('Số lần vượt');

            // Phân loại môi trường
            $table->string('loai_moi_truong')->nullable()->comment('Phân loại môi trường: Nước thải / Khí thải / Ctrsh / Ctrcntt / Ctnh');

            // File đính kèm PDF
            $table->string('tep_pdf')->nullable()->comment('Đường dẫn file PDF kết quả đo đạc');
            $table->unsignedBigInteger('ket_qua_thanh_tra_chung_id')->nullable();
            // Audit
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
        Schema::dropIfExists('ket_qua_quan_trac');
    }
}
