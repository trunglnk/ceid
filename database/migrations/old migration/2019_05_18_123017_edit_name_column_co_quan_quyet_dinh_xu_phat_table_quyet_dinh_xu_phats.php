<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditNameColumnCoQuanQuyetDinhXuPhatTableQuyetDinhXuPhats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {            
            $table->renameColumn('co_quan_quyet_dinh_xu_phat', 'co_quan_quyet_dinh_xu_phat_id');         
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
            $table->renameColumn('co_quan_quyet_dinh_xu_phat_id', 'co_quan_quyet_dinh_xu_phat');      
        });          
    }
}
