<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrangThaiDongBoMien extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('miens', function (Blueprint $table){
            $table->string('trang_thai_dong_bo')->default('chua_dong_bo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('miens', function (Blueprint $table){
            $table->dropColumn('trang_thai_dong_bo');
        });
    }
}
