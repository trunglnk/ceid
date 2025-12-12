<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XuPhatChinh extends Model
{
    protected $fillable = [
        'quyet_dinh_xu_phat_id',
        'so_tien_xu_phat_chinh',
        'noi_dung_vi_pham',
    ];

    public $timestamps = false;

    public function quyetDinhXuPhat()
    {
        return $this->belongsTo('App\QuyetDinhXuPhat', 'quyet_dinh_xu_phat_id')->withDefault();
    }

    // public function hanhViViPhams()
    // {
    //     return $this->hasMany('App\HanhViViPham', 'xu_phat_chinh_id')->orderBy('id');
    // }

    public function hanhViViPhams()
    {
        return $this->hasMany('App\HanhViViPhamNew', 'xu_phat_chinh_id')->orderBy('id');
    }
}
