<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Khoan extends Model
{
    protected $fillable = [
        'ten', 
        'mo_ta', 
        'dieu_id',      
        'loai',      
    ];

    public $timestamps = false;

    public function dieu() {
        return $this->belongsTo('App\Dieu')->withDefault();
    }

    public function mucs() {
        return $this->hasMany('App\Muc', 'khoan_id')->orderBy('ma');
    }
}
