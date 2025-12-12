<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLoaiKhoanPhatThemKhoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('khoans', function (Blueprint $table) {
            $table->boolean('khoan_phat_them')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('khoans', function (Blueprint $table) {
            $table->dropColumn('khoan_phat_them');
        });
    }
}
