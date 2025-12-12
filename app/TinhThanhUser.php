<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TinhThanhUser extends Model
{
    protected $fillable = ['tinh_thanh_id','user_id'];

    public $timestamps = false;

    public function tinhThanh() {
        return $this->belongsTo('App\TinhThanh','tinh_thanh_id')->withDefault();
    }

    public function user() {
        return $this->belongsTo('App\User','tinh_thanh_id')->withDefault();
    }
}
