<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiemTramQTMT extends Model
{
    protected $table = 'diem_tram_qtmt';

    protected $fillable = [
        'ma_dinh_danh','ten_goi',
        'dia_chi_chi_tiet','phuong_xa_ma','phuong_xa_ten',
        'quan_huyen_ma','quan_huyen_ten',
        'tinh_thanh_ma','tinh_thanh_ten',
        'kinh_do','vi_do',
        'kenh_song_ma','kenh_song_ten',
        'luu_vuc_song_ma','luu_vuc_song_ten',
        'loai_diem_qtmt_ma','loai_diem_qtmt_ten',
        'trang_thai_ma','trang_thai_ten',
        'trang_thai_dong_bo',  
        'modified_at'
    ];

    public function phamVi()
    {
        return $this->hasMany(PhamViQTMT::class, 'diem_tram_qtmt_id');
    }
}
