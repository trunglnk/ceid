<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColCoQuanQuyetDinhXuPhatTableQuyetDinhXuPhat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->unsignedInteger('co_quan_quyet_dinh_xu_phat')->nullable();
            $table->foreign('co_quan_quyet_dinh_xu_phat')->references('id')->on('co_quan_to_chucs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->dropColumn('co_quan_quyet_dinh_xu_phat');
        });
    }
}
