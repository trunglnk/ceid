<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddNghiDinhIdToDanhMucHanhViViPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('danh_muc_hanh_vi_vi_phams', function (Blueprint $table) {
            //
            $table->unsignedInteger('nghi_dinh_id')->nullable(); // Thay 'existing_column' bằng cột bạn muốn đặt sau cột mới
            $table->foreign('nghi_dinh_id')->references('id')->on('nghi_dinhs')->onDelete('cascade');;
        });
        DB::statement("
            UPDATE danh_muc_hanh_vi_vi_phams
            SET nghi_dinh_id = nghi_dinhs.id
            FROM nghi_dinhs
            WHERE danh_muc_hanh_vi_vi_phams.phap_ly = nghi_dinhs.code
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('danh_muc_hanh_vi_vi_phams', function (Blueprint $table) {
            $table->dropForeign(['nghi_dinh_id']); // Xóa khóa ngoại trước
            $table->dropColumn('nghi_dinh_id');
        });
    }
}
