<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DanhMucThongSoVuotChuan extends Model
{
    protected $fillable=[
        'ten',
        'ky_hieu_hoa_hoc',
        'type',
        'ma',
        'ma_loai',
        'trang_thai_dong_bo'
    ];
    public function phanLoai(){
        return $this->belongsTo('App\LoaiThongSo','ma_loai', 'ma');
    }  
    
}
