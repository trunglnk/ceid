<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeCoQuanQuyetDinhXuPhatTableQuyetDinhXuPhat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->dropColumn('co_quan_quyet_dinh_xu_phat');
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
            $table->string('co_quan_quyet_dinh_xu_phat')->nullable();
        });
    }
}
