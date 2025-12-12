<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMienIdColumnTinhThanhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tinh_thanhs', function (Blueprint $table) {
            $table->unsignedInteger('mien_id')->nullable();
            $table->foreign('mien_id')->references('id')->on('miens');
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
            $table->dropColumn('mien_id');
        });
    }
}
