<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNotNullKetQuaThanhTraChungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_chungs', function (Blueprint $table) {
            $table->string('kinh_do')->nullable()->change();
            $table->string('vi_do')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ket_qua_thanh_tra_chungs', function (Blueprint $table) {
            $table->string('kinh_do')->nullable(false)->change();
            $table->string('vi_do')->nullable(false)->change();
        });
    }
}
