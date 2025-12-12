<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHanhViViPhamsPhatTangThemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('nhom_hanh_vi_vi_phams', function ($table) {
            $table->increments('id');
            $table->dropColumn('phat_tang_them_id');
        });
        Schema::create('nhom_hanh_vi_vi_pham_phat_tang_them', function ($table) {
            $table->increments('id')->unsigned();
            $table->integer('nhom_hanh_vi_vi_pham_id')->unsigned();
            $table->integer('phat_tang_them_id')->unsigned();
            $table->string('type');

            $table->foreign('nhom_hanh_vi_vi_pham_id')
                ->references('id')
                ->on('nhom_hanh_vi_vi_phams')
                ->onDelete('cascade');

            $table->foreign('phat_tang_them_id')
                ->references('id')
                ->on('phat_tang_thems')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nhom_hanh_vi_vi_pham_phat_tang_them');
        Schema::table('nhom_hanh_vi_vi_phams', function ($table) {
            $table->dropColumn('id');
            $table->unsignedInteger('phat_tang_them_id')->nullable();
            $table->foreign('phat_tang_them_id')
                ->references('id')
                ->on('phat_tang_thems');
        });
    }
}
