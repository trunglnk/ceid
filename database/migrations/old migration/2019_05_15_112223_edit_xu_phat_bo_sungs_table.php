<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditXuPhatBoSungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xu_phat_bo_sungs', function (Blueprint $table) {
            $table->dropForeign('xu_phat_bo_sungs_xu_phat_bo_sung_id_foreign');
            $table->dropColumn('xu_phat_bo_sung_id');
            $table->decimal('so_tien_xu_phat_bo_sung',18,0)->nullable();  
            $table->text('noi_dung_xu_phat_bo_sung')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xu_phat_bo_sungs', function (Blueprint $table) {
            $table->unsignedInteger('xu_phat_bo_sung_id')->nullable();
            $table->foreign('xu_phat_bo_sung_id')->references('id')->on('xu_phat_bo_sungs');
            $table->dropColumn('so_tien_xu_phat_bo_sung');
            $table->dropColumn('noi_dung_xu_phat_bo_sung');
        });
    }
}
