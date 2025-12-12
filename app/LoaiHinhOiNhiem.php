<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoaiHinhOiNhiem extends Model
{
    protected $fillable = [
        'ten',
        'ma',
        'inactive', 
        'trang_thai_dong_bo'    
    ];

    protected static function boot(){
        parent::boot();       
        static::addGlobalScope(new \App\Scopes\ActiveScope);
    }
}
