<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HanhViViPhamNew extends Model
{
    protected $fillable = ['xu_phat_chinh_id', 'danh_muc_hanh_vi_id'];
    public function xuPhatChinh()
    {
        return $this->belongsTo('App\XuPhatChinh', 'xu_phat_chinh_id')->withDefault();
    }
    public function hanhVi()
    {
        return $this->belongsTo('App\DanhMucHanhViViPham', 'danh_muc_hanh_vi_id')->withDefault();
    }
}
