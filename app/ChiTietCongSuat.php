<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiTietCongSuat extends Model
{
    protected $fillable = [
        'co_so_san_xuat_id',
        'don_vi_id',
        'thong_so',
        'loai_hinh_id',
        'thong_so_hoat_dong',
        'don_vi_hoat_dong_id'
    ];


    public $timestamps = false;


    public function donVi()
    {
        return $this->belongsTo('App\ChuyenDoiDonVi', 'don_vi_id')->withDefault();
    }
    public function donViHD()
    {
        return $this->belongsTo('App\ChuyenDoiDonVi', 'don_vi_hoat_dong_id')->withDefault();
    }
    public function loaiHinh()
    {
        return $this->belongsTo('App\LoaiHinhCoSo', 'loai_hinh_id')->withDefault();
    }
    public function coSoSanXuat()
    {
        return $this->belongsTo('App\CoSoSanXuat', 'co_so_san_xuat_id')->withDefault();
    }
    public function donViThietKe()
    {
        return $this->belongsTo('App\ChuyenDoiDonVi', 'don_vi_id')->withDefault();
    }
    public function donViHoatDong()
    {
        return $this->belongsTo('App\ChuyenDoiDonVi', 'don_vi_hoat_dong_id')->withDefault();
    }
    public function loaiHinhCoSo()
    {
        return $this->belongsTo('App\LoaiHinhCoSo', 'loai_hinh_id')->withDefault();
    }
}
