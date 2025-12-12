<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoaiHinhCoSo extends Model
{
    protected $fillable = [
        'ten',
        'parent_id',
        'ma',
        'inactive',
        'trang_thai_dong_bo'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new \App\Scopes\ActiveScope);
    }
}
