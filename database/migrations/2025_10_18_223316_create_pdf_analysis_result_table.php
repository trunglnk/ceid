<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePdfAnalysisResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdf_analysis_result', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('attachment_id')->nullable();
            $table->string('hash')->nullable();
            $table->text('summary')->nullable();
            $table->text('content')->nullable();
            $table->jsonb('json')->nullable();
            $table->timestamps();

            $table->foreign('attachment_id')
                ->references('id')->on('attachments')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pdf_analysis_result');
    }
}
