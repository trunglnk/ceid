<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mien extends Model
{
    protected $table='miens';
    public $incrementing =false;
    protected $fillable=['ma','ten','mo_ta', 'ma_dinh_danh', 'trang_thai_dong_bo'];
    public $timestamps=false;
}
