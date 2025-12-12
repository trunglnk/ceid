<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XuPhatBoSung extends Model
{
    protected $fillable = [
        'quyet_dinh_xu_phat_id',
        'so_tien_xu_phat_bo_sung',
        'noi_dung_xu_phat_bo_sung',
        'xu_phat_bo_sungs',
        'dinh_chi',
    ];
    public $timestamps = false;

    public function quyetDinhXuPhat()
    {
        return $this->belongsTo('App\QuyetDinhXuPhat', 'quyet_dinh_xu_phat_id')->withDefault();
    }

    public function chiTietXuPhatBoSungs()
    {
        return $this->hasMany('App\ChiTietXuPhatBoSung', 'xu_phat_bo_sung_id')->orderBy('id');
    }
}
