<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoSoSanXuatLoaiHinhONhiem extends Model
{
    protected $fillable = [
        'co_so_san_xuat_id', 
        'loai_hinh_o_nhiem_id'
    ];
}
