<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LuuVucSong extends Model
{
    protected $table='luu_vuc_songs';
    protected $fillable = [
        'ma',
        'ten',
        'mo_ta',
        'trang_thai_dong_bo',
        'chieu_dai',
        'dien_tich'
    ];
    protected $dates = ['created_at', 'updated_at'];
    public function tinhthanhs() {
        return $this->hasMany('App\TinhThanh','luu_vuc_song_id','id');
    }

    public function tinhThanhNews(){
        return $this->belongsToMany('App\TinhThanh','tinh_thanh_luu_vuc_songs', 'luu_vuc_song_id','tinh_thanh_id');
    }
    
}
