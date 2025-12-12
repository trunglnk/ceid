<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiTietBienPhapKhacPhucHauQua extends Model
{
    protected $fillable = [
        'bien_phap_khac_phuc_hau_qua_id',
        'cac_bien_phap_khac_phuc_hau_qua_id',
    ];
    
    public $timestamps = false;

    public function bienPhapKhacPhucHauQua(){
        return $this->belongsTo('App\BienPhapKhacPhucHauQua','bien_phap_khac_phuc_hau_qua_id');
    }

    public function cacBienPhapKhacPhucHauQua(){
        return $this->belongsTo('App\CacBienPhapKhacPhucHauQua','cac_bien_phap_khac_phuc_hau_qua_id');
    }
}
