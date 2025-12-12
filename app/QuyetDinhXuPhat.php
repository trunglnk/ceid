<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class QuyetDinhXuPhat extends Model
{
    protected $fillable = [
        'id',
        'so_quyet_dinh',
        'co_quan_quyet_dinh_xu_phat_id',
        'thoi_gian_ban_hanh',                 
        'so_quyet_dinh_sua_doi',
        'ngay_sua_doi_quyet_dinh',                
        'ket_qua_thanh_tra_id',
        'ghi_chu',  
        'ma_dinh_danh',
        'thoi_gian_dong_bo',
        'trang_thai_dong_bo'                      
    ];
    
    public function setThoiGianBanHanhAttribute($value)
    {       
        if (!empty($value)) {
            if ($value instanceof Carbon) {
                $this->attributes['thoi_gian_ban_hanh'] = $value;
            } else if (is_numeric($value)) {
                $this->attributes['thoi_gian_ban_hanh'] = Carbon::createFromTimestamp(($value - 25569) * 86400)->startOfMonth();
            } else {
                $this->attributes['thoi_gian_ban_hanh'] = Carbon::createFromFormat(config('app.format_date'), $value);
            }
        } else {
            $this->attributes['thoi_gian_ban_hanh'] = null;
        }
        
    }
   
    public function getThoiGianBanHanhAttribute()
    {
        if (!empty($this->attributes['thoi_gian_ban_hanh'])) {
            return Carbon::parse($this->attributes['thoi_gian_ban_hanh'])->format(config('app.format_date'));
        } else {
            return null;
        }
    }

    public function setNgaySuaDoiQuyetDinhAttribute($value)
    {
       
        if (!empty($value)) {
            if ($value instanceof Carbon) {
                $this->attributes['ngay_sua_doi_quyet_dinh'] = $value;
            } else if (is_numeric($value)) {
                $this->attributes['ngay_sua_doi_quyet_dinh'] = Carbon::createFromTimestamp(($value - 25569) * 86400)->startOfMonth();
            } else {

                $this->attributes['ngay_sua_doi_quyet_dinh'] = Carbon::createFromFormat(config('app.format_date'), $value);

            }
        } else {
            $this->attributes['ngay_sua_doi_quyet_dinh'] = null;
        }
    }

    public function getNgaySuaDoiQuyetDinhAttribute()
    {
        if (!empty($this->attributes['ngay_sua_doi_quyet_dinh'])) {
            return Carbon::parse($this->attributes['ngay_sua_doi_quyet_dinh'])->format(config('app.format_date'));
        } else {
            return null;
        }
    }

    public function coQuanQuyetDinhXuPhat()
    {
        return $this->belongsTo('App\CoQuanToChuc', 'co_quan_quyet_dinh_xu_phat_id')->withDefault();
    }

    public function ketQuaThanhTra()
    {
        return $this->belongsTo('App\KetQuaThanhTra', 'ket_qua_thanh_tra_id')->withDefault();
    }

    public function xuPhatBoSung()
    {
        return $this->hasMany('App\XuPhatBoSung', 'quyet_dinh_xu_phat_id')->orderBy('id');
    } 
    
    public function xuPhatChinh()
    {
        return $this->hasMany('App\XuPhatChinh', 'quyet_dinh_xu_phat_id')->orderBy('id');
    }

    public function bienPhapKhacPhucHauQua()
    {
        return $this->hasMany('App\BienPhapKhacPhucHauQua', 'quyet_dinh_xu_phat_id')->orderBy('id');
    }    
}