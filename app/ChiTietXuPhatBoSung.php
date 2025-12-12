<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiTietXuPhatBoSung extends Model
{
    protected $fillable = [
        'xu_phat_bo_sung_id',
        'loai_xu_phat_bo_sung_id',        
    ];

    protected $table = 'chi_tiet_xu_phat_bo_sungs';
    
    public $timestamps = false;
    
    public function xuPhatBoSung() {
        return $this->belongsTo('App\XuPhatBoSung','xu_phat_bo_sung_id');
    }

    public function loaiXuPhatBoSung() {
        return $this->belongsTo('App\LoaiXuPhatBoSung','loai_xu_phat_bo_sung_id');
    }
}
