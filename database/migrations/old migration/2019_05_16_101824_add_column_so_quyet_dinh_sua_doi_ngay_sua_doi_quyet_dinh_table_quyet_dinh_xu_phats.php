<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSoQuyetDinhSuaDoiNgaySuaDoiQuyetDinhTableQuyetDinhXuPhats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {                                    
            $table->string('so_quyet_dinh_sua_doi')->nullable();                  
            $table->dateTime('ngay_sua_doi_quyet_dinh')->nullable();                                                     
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
            $table->dropColumn('so_quyet_dinh_sua_doi');                                                      
            $table->dropColumn('ngay_sua_doi_quyet_dinh');                                        
        });
    }
}
