<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuyChuanVns extends Model
{
    //
    protected $fillable = [
        'ten',
        'mo_ta',
    ];

    public function quyChuanThuNghiem()
    {
        return $this->hasMany(QuyChuanThuNghiems::class, 'quy_chuan_vn_id');
    }
}
