<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuyChuanThuNghiems extends Model
{
    //
    protected $fillable = [
        'thu_nghiem_id',
        'quy_chuan_vn_id',
        'gia_tri',
    ];

    public function thuNghiem()
    {
        return $this->belongsTo(ThuNghiems::class, 'thu_nghiem_id');
    }

    public function quyChuanVn()
    {
        return $this->belongsTo(QuyChuanVns::class, 'quy_chuan_vn_id');
    }
}
