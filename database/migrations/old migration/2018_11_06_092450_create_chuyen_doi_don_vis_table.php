<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChuyenDoiDonVisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chuyen_doi_don_vis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten');
            $table->string('loai')->nullable();
            $table->double('ty_le')->nullable();
            $table->string('mo_ta')->nullable();
            $table->boolean('don_vi_goc')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chuyen_doi_don_vis');
    }
}
