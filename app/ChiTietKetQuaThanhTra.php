<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiTietKetQuaThanhTra extends Model
{
    protected $fillable = [
        'ket_qua_thanh_tra_id',
        'co_so_san_xuat_id',                             
    ];

    public $timestamps = false;
    
    public function coSoSanXuat(){
        return $this->belongsTo('App\CoSoSanXuat','co_so_san_xuat_id','id')->withDefault();
    }

    public function ketQuaThanhTra(){
        return $this->belongsTo('App\KetQuaThanhTra','ket_qua_thanh_tra_id','id')->withDefault();
    }
}
