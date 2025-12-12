<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KetQuaThanhTraChungLoaiHinhHoatDong extends Model
{
    protected $fillable = [
        'id',
        'ket_qua_thanh_tra_chung_id',
        'loai_hinh_co_so_id',
        'thong_so_hoat_dong',
        'don_vi_hoat_dong_id',
        'thong_so_thiet_ke',
        'don_vi_thiet_ke_id',  
        'ghi_chu'                   
    ];

    public $timestamps = false;

    public function ketQuaThanhTra(){
        return $this->belongsTo('App\KetQuaThanhTraChung','ket_qua_thanh_tra_chung_id')->withDefault();
    }

    public function loaiHinhCoSo(){
        return $this->belongsTo('App\LoaiHinhCoSo','loai_hinh_co_so_id')->withDefault();
    }    

    public function donViHoatDong()
    {
        return $this->belongsTo('App\ChuyenDoiDonVi', 'don_vi_hoat_dong_id')->withDefault();
    }    
    
    public function donViThietKe()
    {
        return $this->belongsTo('App\ChuyenDoiDonVi', 'don_vi_thiet_ke_id')->withDefault();
    }    
}
