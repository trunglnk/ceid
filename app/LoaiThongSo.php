<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoaiThongSo extends Model
{
    // protected $guarded = [];
    protected $fillable = [
        'ma', 'ten', 'trang_thai_dong_bo'
    ];
}
