<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrangThaiDongBoToDiemTramQtmtTable extends Migration
{
    public function up(): void
    {
        Schema::table('diem_tram_qtmt', function (Blueprint $table) {
            $table->string('trang_thai_dong_bo')->nullable()->after('trang_thai_ten');
        });
    }

    public function down(): void
    {
        Schema::table('diem_tram_qtmt', function (Blueprint $table) {
            $table->dropColumn('trang_thai_dong_bo');
        });
    }
}
