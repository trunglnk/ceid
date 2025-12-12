<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DanhMucHanhViViPham extends Model
{
    protected $guarded = [];
    //

    public function nghiDinhs()
    {
        return $this->belongsTo('App\NghiDinh', 'nghi_dinh_id', 'id');
    }
}
