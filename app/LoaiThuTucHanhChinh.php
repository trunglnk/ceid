<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoaiThuTucHanhChinh extends Model
{
    protected $table='loai_thu_tuc_hanh_chinhs';
    protected $fillable = [
        'ma',
        'ten',
        'mo_ta',
        'order',
        'phan_loai',
    ];
    protected $dates = ['created_at', 'updated_at'];

    
}
