<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhuongXa extends Model
{
    //
    protected $guarded = [];
    public function quan_huyen() {
        return $this->belongsTo('App\QuanHuyen', 'quan_huyen_id')->withDefault();
    }
}
