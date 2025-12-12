<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TinhThanhLuuVucSong extends Model
{
    protected $fillable = ['tinh_thanh_id', 'luu_vuc_song_id', 'quan_huyen_id'];
}
