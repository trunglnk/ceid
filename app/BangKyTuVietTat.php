<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BangKyTuVietTat extends Model
{
    protected $fillable = [
        'chu_viet_tat',
        'nguyen_nghia'
    ];
    
    public $timestamps = false;
}
