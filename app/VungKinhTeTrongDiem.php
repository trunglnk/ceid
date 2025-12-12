<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VungKinhTeTrongDiem extends Model
{
    protected $table='vung_kinh_te_trong_diems';
    public $incrementing =false;
    protected $fillable=['ma','ten','mo_ta'];
    public $timestamps=false;
}
