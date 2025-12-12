<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuanHuyen extends Model
{
    protected $fillable = ['ma','ten','tinh_thanh_id','trang_thai','mo_ta', 'trang_thai_dong_bo', 'ma_dinh_danh'];

    public function tinh_thanh() {
        return $this->belongsTo('App\TinhThanh', 'tinh_thanh_id')->withDefault();
    }
}
