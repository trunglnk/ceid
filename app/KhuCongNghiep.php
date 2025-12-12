<?php

namespace App;

use App\Helpers\UpperHelpers;
use Illuminate\Database\Eloquent\Model;

class KhuCongNghiep extends Model
{
    protected $fillable = [
        'ma',
        'ten',
        'dia_chi',
        'loai_khu_cong_nghiep',
        'xa_phuong',
        'quan_huyen_id',
        'tinh_thanh_id',
        'inactive'
    ];
    public function setTenAttribute($value)
    {

        if (!empty($value)) {
            $this->attributes['ten'] = UpperHelpers::upper($value);
        } else {
            $this->attributes['ten'] = null;
        }
    }

    public function loaiKhuCongNghiep()
    {
        return $this->belongsTo('App\LoaiKhuCongNghiep', 'loai_khu_cong_nghiep', 'ma')->withDefault();
    }

    public function quanHuyen()
    {
        return $this->belongsTo('App\QuanHuyen')->withDefault();
    }

    public function tinhThanh()
    {
        return $this->belongsTo('App\TinhThanh')->withDefault();
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new \App\Scopes\UserScope);
    }
}
