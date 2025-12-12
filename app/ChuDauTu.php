<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChuDauTu extends Model
{
    protected $fillable = [
        'id',
        'ten',
        'tinh_thanh_id',
        'quan_huyen_id',
        'email',
        'nguoi_dai_dien',
        'so_dien_thoai',
        'fax',
        'dia_chi',
        'email',
        'ngay_tao',
        'xa_phuong',
        'ngay_cap_nhat',
        'ma_so_qlctnh',
        'so_giay_chung_nhan_dang_ky_kinh_doanh',
        'co_quan_cap_giay_chung_nhan_dang_ky_kinh_doanh',
        'ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh',
        'lan_cap_giay_chung_nhan_dang_ky_kinh_doanh',
        'ngay_cap_lan_dau_giay_chung_nhan_dang_ky_kinh_doanh',
        'so_giay_chung_nhan_dau_tu',
        'noi_cap_giay_chung_nhan_dau_tu',
        'ngay_cap_giay_chung_nhan_dau_tu',
        'lan_cap_giay_chung_nhan_dau_tu',
        'ngay_cap_lan_dau_giay_chung_nhan_dau_tu',    
        'ghi_chu',
        'co_quan_cap_giay_chung_nhan_kinh_doanh',
        'noi_cap_giay_chung_nhan_dau_tu'  ,
        'trang_thai_dong_bo',
        'loai_du_lieu',
        'ma_dinh_danh'
    ];
    
    public function coQuanCapGiayKinhDoanh(){
        return $this->belongsTo('App\CoQuanToChuc','co_quan_cap_giay_chung_nhan_kinh_doanh', 'id');
    }  
    public function tinhThanh()
    {
        return $this->belongsTo('App\TinhThanh', 'tinh_thanh_id')->withDefault();
    }

    public function quanHuyen()
    {
        return $this->belongsTo('App\QuanHuyen', 'quan_huyen_id')->withDefault();
    }

    public function organization()
    {
        return $this->belongsTo('App\Organization', 'id', 'chu_dau_tu_id')->withDefault();
    }
}
