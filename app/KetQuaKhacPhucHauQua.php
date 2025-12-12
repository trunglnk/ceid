<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KetQuaKhacPhucHauQua extends Model
{
    protected $fillable = [
        'id',
        'so_quyet_dinh',        
        'so_tien',
        'thoi_gian_ban_hanh',
        'da_nop_phat',        
        'nop_mot_phan',        
        'da_khac_phuc',        
        'ket_qua_thanh_tra_id',
        'da_bao_cao',
        'tinh_trang_bao_cao',
        'ngay_ban_hanh_bao_cao',
        'so_hieu_bao_cao',
        'noi_dung_da_khac_phuc',
        'noi_dung_chua_khac_phuc',
        'so_bao_cao',
    ];   

    protected $dates = ['thoi_gian_ban_hanh', 'ngay_ban_hanh_bao_cao'];

    public function setThoiGianBanHanhAttribute($value)
    {
       
        if (!empty($value)) {
            if ($value instanceof Carbon) {
                $this->attributes['thoi_gian_ban_hanh'] = $value;
            } else if (is_numeric($value)) {
                $this->attributes['thoi_gian_ban_hanh'] = Carbon::createFromTimestamp(($value - 25569) * 86400)->startOfDay();
            } else {

                $this->attributes['thoi_gian_ban_hanh'] = Carbon::createFromFormat(config('app.format_date'), $value)->startOfDay();

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

    public function setNgayBanHanhBaoCaoAttribute($value)
    {        
        if (!empty($value)) {
            if ($value instanceof Carbon) {
                $this->attributes['ngay_ban_hanh_bao_cao'] = $value;
            } else if (is_numeric($value)) {
                $this->attributes['ngay_ban_hanh_bao_cao'] = Carbon::createFromTimestamp(($value - 25569) * 86400)->startOfDay();
            } else {

                $this->attributes['ngay_ban_hanh_bao_cao'] = Carbon::createFromFormat(config('app.format_date'), $value)->startOfDay();

            }
        } else {
            $this->attributes['ngay_ban_hanh_bao_cao'] = null;
        }

    }

    public function getNgayBanHanhBaoCaoAttribute()
    {
        if (!empty($this->attributes['ngay_ban_hanh_bao_cao'])) {
            return Carbon::parse($this->attributes['ngay_ban_hanh_bao_cao'])->format(config('app.format_date'));
        } else {
            return null;
        }
    }

    public function setSoTienAttribute($value) {
        if(empty($value)){
            $this->attributes['so_tien']=null;
        }
        else{
            $this->attributes['so_tien'] = str_replace(',', '', $value);
        }
    }

    public function ketQuaThanhTra(){
        return $this->belongsTo('App\KetQuaThanhTra','ket_qua_thanh_tra_id')->withDefault();
    }    
}
