<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestAndResponseToDongBoMtqgLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dong_bo_mtqg_logs', function (Blueprint $table) {
            $table->json('yeu_cau')->nullable()->after('mo_ta');
            $table->json('ket_qua')->nullable()->after('yeu_cau');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dong_bo_mtqg_logs', function (Blueprint $table) {
            $table->dropColumn(['yeu_cau', 'ket_qua']);
        });
    }
}
