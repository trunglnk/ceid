<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLatLngToMiensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('miens', function (Blueprint $table) {
        $table->double('kinh_do',19,15)->default(0);
        $table->double('vi_do',19,15)->default(0);
      });
      Schema::table('tinh_thanhs', function (Blueprint $table) {
        $table->double('kinh_do',19,15)->default(0);
        $table->double('vi_do',19,15)->default(0);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('miens', function (Blueprint $table) {
        $table->dropColumn('kinh_do');
        $table->dropColumn('vi_do');
      });
      Schema::table('tinh_thanhs', function (Blueprint $table) {
        $table->dropColumn('kinh_do');
        $table->dropColumn('vi_do');
      });
    }
}
