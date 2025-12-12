<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChuyenDoiDonVi extends Model
{
    public $timestamps = false;
    protected $fillable=[
        'ten',
        'loai',
        'ty_le',
        'don_vi_goc',
        'mo_ta'
    ];
}
