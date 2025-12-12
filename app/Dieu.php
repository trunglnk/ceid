<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dieu extends Model
{
    protected $fillable = [
        'ten', 
        'mo_ta',       
        'loai',       
    ];

    public $timestamps = false;

    public function khoans(){
        return $this->hasMany('App\Khoan', 'dieu_id')->orderBy('id');
    }
    public function loaiXuPhatBoSungs(){
        return $this->hasMany('App\LoaiXuPhatBoSung', 'dieu_id');
    }
    public function cacBienPhapKhacPhucHauQuas(){
        return $this->hasMany('App\CacBienPhapKhacPhucHauQua', 'dieu_id');
    }
}
