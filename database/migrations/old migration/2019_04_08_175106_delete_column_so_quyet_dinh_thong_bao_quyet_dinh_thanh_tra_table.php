<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnSoQuyetDinhThongBaoQuyetDinhThanhTraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->dropColumn('so_quyet_dinh_thong_bao');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {      
        Schema::table('quyet_dinh_thanh_tras', function (Blueprint $table) {
            $table->string('so_quyet_dinh_thong_bao')->nullable();
        });
    }
}
