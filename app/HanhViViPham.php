<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HanhViViPham extends Model
{
    protected $fillable=['xu_phat_chinh_id','dieu_id','khoan_id','muc_id'];
    public function dieu()
    {
        return $this->belongsTo('App\Dieu', 'dieu_id')->withDefault();
    }
    public function khoan()
    {
        return $this->belongsTo('App\Khoan', 'khoan_id')->withDefault();
    }
    public function muc()
    {
        return $this->belongsTo('App\Muc', 'muc_id')->withDefault();
    }
    public function xuPhatChinh()
    {
        return $this->belongsTo('App\XuPhatChinh', 'xu_phat_chinh_id')->withDefault();
    }
}
