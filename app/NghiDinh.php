<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NghiDinh extends Model
{
    //
    protected $table = 'nghi_dinhs';
    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    public function danhMucHanhViViPhams()
    {
        return $this->hasMany('App\DanhMucHanhViViPham', 'nghi_dinh_id', 'id');
    }
}
