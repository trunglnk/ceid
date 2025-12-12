<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThuNghiems extends Model
{
    //
    protected $fillable = [
        'stt',
        'thong_so',
        'don_vi',
        'phuong_phap_thu',
        'ket_qua',
        'phieu_thu_nghiem_id',
    ];

    public function quyChuanThuNghiem()
    {
        return $this->hasMany(QuyChuanThuNghiems::class, 'thu_nghiem_id');
    }

    public function phieuThuNghiem()
    {
        return $this->belongsTo(PhieuThuNghiems::class, 'phieu_thu_nghiem_id');
    }
}
