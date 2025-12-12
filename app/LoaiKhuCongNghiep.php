<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoaiKhuCongNghiep extends Model
{
    protected $table='loai_khu_cong_nghieps';
    public $incrementing =false;
    protected $fillable=['ma','ten', 'order', 'ma_muc', 'trang_thai_dong_bo'];
    public $timestamps=false;
}
