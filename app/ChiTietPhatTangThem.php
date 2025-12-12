<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiTietPhatTangThem extends Model
{
    protected $fillable = [
        'chi_tiet_vi_pham_xa_thai_id',
        'phat_tang_them_id',
        'don_vi_id',
        'thong_so_id',
        'ket_qua',
        'so_lan_vuot',
    ];

    public function thongSo()
    {
        return $this->belongsTo(DanhMucThongSoVuotChuan::class, 'thong_so_id', 'id')->withDefault();
    }

    public function donVi()
    {
        return $this->belongsTo(ChuyenDoiDonVi::class, 'don_vi_id', 'id')->withDefault();
    }

    public function phatTangThem()
    {
        return $this->belongsTo(PhatTangThem::class, 'phat_tang_them_id', 'id')->withDefault();
    }
}
