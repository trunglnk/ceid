<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhamViQtmtTable extends Migration
{
    public function up(): void
    {
        Schema::create('pham_vi_qtmt', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('diem_tram_qtmt_id');
            $table->foreign('diem_tram_qtmt_id')
                    ->references('id')->on('diem_tram_qtmt')
                    ->onDelete('cascade');

            $table->string('thong_so_ma')->nullable();
            $table->string('thong_so_ten')->nullable();
            $table->string('quy_chuan_ma')->nullable();
            $table->string('quy_chuan_ten')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pham_vi_qtmt');
    }
};