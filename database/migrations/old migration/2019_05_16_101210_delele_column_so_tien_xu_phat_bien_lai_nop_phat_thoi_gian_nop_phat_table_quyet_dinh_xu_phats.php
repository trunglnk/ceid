<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleleColumnSoTienXuPhatBienLaiNopPhatThoiGianNopPhatTableQuyetDinhXuPhats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {                        
            $table->dropColumn('so_tien_xu_phat');                                                      
            $table->dropColumn('bien_lai_nop_phat');                                                      
            $table->dropColumn('thoi_gian_nop_phat');                                                      
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
            $table->decimal('so_tien_xu_phat',18,0)->nullable();                  
            $table->string('bien_lai_nop_phat')->nullable();                  
            $table->dateTime('thoi_gian_nop_phat')->nullable();                  
        });
    }
}
