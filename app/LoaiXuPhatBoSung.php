<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoaiXuPhatBoSung extends Model
{
    protected $table='loai_xu_phat_bo_sungs';
    public $incrementing =false;
    protected $fillable=['id','ten','mo_ta', 'dieu_id'];
    public $timestamps=false;
}
