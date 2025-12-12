<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiaDiemCoSoSanXuat extends Model
{
    protected $fillable=[
        'co_so_san_xuat_id',
        'phuong_xa_id',
        'quan_huyen_id',
        'tinh_thanh_id',
    ];
}
