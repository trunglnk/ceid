<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiTietViPhamXaThai extends Model
{
    protected $fillable = [
        'nhom_hanh_vi_vi_pham_id',
        'muc_vi_pham_id',
        'khoan_vi_pham_id',
        'thong_so_id',
        'don_vi_id',
        'co_so_san_xuat_id',
        'ket_qua',
        'so_lan_vuot',
        'type',
        'dia_diem_lay_mau'
    ];

    public function donVi()
    {
        return $this->belongsTo(ChuyenDoiDonVi::class, 'don_vi_id');
    }

    public function chiTietPhatTangThems()
    {
        return $this->hasMany(ChiTietPhatTangThem::class);
    }

    public function thongSo()
    {
        return $this->belongsTo(DanhMucThongSoVuotChuan::class, 'thong_so_id');
    }

    public function khoan()
    {
        return $this->belongsTo(Khoan::class, 'khoan_vi_pham_id');
    }

    public function muc()
    {
        return $this->belongsTo(Muc::class, 'muc_vi_pham_id');
    }

    public function coSoSanXuat()
    {
        return $this->belongsTo(CoSoSanXuat::class, 'co_so_san_xuat_id');
    }
}
