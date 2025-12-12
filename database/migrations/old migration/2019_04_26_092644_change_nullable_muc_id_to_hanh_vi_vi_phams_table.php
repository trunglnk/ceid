<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNullableMucIdToHanhViViPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hanh_vi_vi_phams', function($table)
        {
            $table->unsignedInteger('muc_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hanh_vi_vi_phams', function($table)
        {
            $table->unsignedInteger('muc_id')->nullable(false)->change();
        });
    }
}