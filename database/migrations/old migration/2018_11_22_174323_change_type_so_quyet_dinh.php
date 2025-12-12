<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeSoQuyetDinh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->string('so_quyet_dinh')->nullable()->change();
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
            $table->string('so_quyet_dinh')->change();
        });
    }
}
