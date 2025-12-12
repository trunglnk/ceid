<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumVungKinhTeTrongDiem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tinh_thanhs', function (Blueprint $table) {
            $table->unsignedInteger('vung_kinh_te_trong_diem_id')->nullable();
            $table->foreign('vung_kinh_te_trong_diem_id')->references('id')->on('vung_kinh_te_trong_diems');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tinh_thanhs', function (Blueprint $table) {
            $table->dropColumn('vung_kinh_te_trong_diem_id');
        });
    }
}
