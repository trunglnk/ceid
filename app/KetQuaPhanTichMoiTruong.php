<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KetQuaPhanTichMoiTruong extends Model
{

    protected $table = 'ket_qua_phan_tich_moi_truong';

    protected $fillable = [
        'ket_qua_thanh_tra_chung_id',
        'dia_diem',
        'vi_tri',
        'loai_mau',
        'kinh_do',
        'vi_do',
        'thong_so',
        'don_vi_tinh',
        'phuong_phap_phan_tich',
        'ket_qua',
        'gia_tri_gioi_han',
        'so_lan_vuot',
        'loai_moi_truong',
        'tep_pdf',
    ];

    public function ketQuaThanhTra()
    {
        return $this->belongsTo('App\KetQuaThanhTraChung', 'ket_qua_thanh_tra_chung_id')->withDefault();
    }

    public function attachment()
    {
        return $this->hasOne(Attachment::class, 'reference_id')->where('reference_type', 'ket_qua_phan_tich_moi_truong');
    }
}
