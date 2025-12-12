<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TinhThanh extends Model
{
    protected $fillable = ['ma','ten','trang_thai','mo_ta', 'mien_id','vung_kinh_te_trong_diem_id','luu_vuc_song_id', 'trang_thai_dong_bo'];

    public function quanHuyens() {
        return $this->hasMany('App\QuanHuyen','tinh_thanh_id','id');
    }

    public function mien() {
        return $this->belongsTo('App\Mien','mien_id')->withDefault();
    }

    public function vungKinhTeTrongDiem() {
        return $this->belongsTo('App\VungKinhTeTrongDiem','vung_kinh_te_trong_diem_id')->withDefault();
    }

    public function luuVucSong() {
        return $this->belongsTo('App\LuuVucSong','luu_vuc_song_id')->withDefault();
    }

    public function diemTramQtmtTheoTinh()
    {
        return $this->hasMany(\App\DiemTramQTMT::class, 'tinh_thanh_ma', 'ma');
    }

    protected static function boot()
    {
       parent::boot();

       static::addGlobalScope(new \App\Scopes\TinhThanhIdScope);
    }
}
