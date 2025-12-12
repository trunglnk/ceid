<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CacBienPhapKhacPhucHauQua extends Model
{
    protected $table = 'cac_bien_phap_khac_phuc_hau_quas';
    
    public $incrementing =false;
    
    protected $fillable = [
        'id',
        'ten',
        'mo_ta',
        'dieu_id',
    ];

    public $timestamps=false;
}
