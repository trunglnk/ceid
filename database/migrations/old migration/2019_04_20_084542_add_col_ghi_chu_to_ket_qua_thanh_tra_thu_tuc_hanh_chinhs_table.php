<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColGhiChuToKetQuaThanhTraThuTucHanhChinhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ket_qua_thanh_tra_thu_tuc_hanh_chinhs', function (Blueprint $table) {
             $table->text('ghi_chu')->nullable();                        
        });

        Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->string('so_quyet_dinh_sua_doi')->nullable(); 
            $table->date('ngay_sua_doi_quyet_dinh')->nullable();                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('ket_qua_thanh_tra_thu_tuc_hanh_chinhs', function (Blueprint $table) {
            $table->dropColumn('ghi_chu');                               
        });

         Schema::table('quyet_dinh_xu_phats', function (Blueprint $table) {
            $table->dropColumn('so_quyet_dinh_sua_doi'); 
            $table->dropColumn('ngay_sua_doi_quyet_dinh');                        
        });
    }
}