<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColInCoSoSanXuat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->text('nguyen_lieu_chinh_su_dung')->nullable();
            $table->text('nhien_lieu_chinh_su_dung')->nullable();
            $table->text('hoa_chat_su_dung')->nullable();
            $table->text('nguon_nuoc_su_dung')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_so_san_xuats', function (Blueprint $table) {
            $table->dropColumn('nguyen_lieu_chinh_su_dung');
            $table->dropColumn('nhien_lieu_chinh_su_dung');
            $table->dropColumn('hoa_chat_su_dung');
            $table->dropColumn('nguon_nuoc_su_dung');
        });
    }
}
