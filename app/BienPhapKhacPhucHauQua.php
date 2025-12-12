<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BienPhapKhacPhucHauQua extends Model
{
    protected $fillable = [
        'id',
        'quyet_dinh_xu_phat_id',
        'so_tien_chung_cau',
        'noi_dung_bien_phap_khac_phuc_hau_qua',
    ];
    
    public $timestamps = false;

    public function quyetDinhXuPhat(){
        return $this->belongsTo('App\QuyetDinhXuPhat','quyet_dinh_xu_phat_id')->withDefault();
    }

    public function chiTietBienPhapKhacPhucHauQuas()
    {
        return $this->hasMany('App\ChiTietBienPhapKhacPhucHauQua', 'bien_phap_khac_phuc_hau_qua_id')->orderBy('id');
    }        
}
