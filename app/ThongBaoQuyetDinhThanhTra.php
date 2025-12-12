<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ThongBaoQuyetDinhThanhTra extends Model
{
    protected $fillable=[
        'so_thong_bao_quyet_dinh',
        'co_quan_thong_bao_id',        
        'thoi_gian',
        'ket_qua_thanh_tra_id'
    ];

    public function coQuanThongBao()
    {
        return $this->belongsTo('App\CoQuanToChuc', 'co_quan_thong_bao_id')->withDefault();
    }

    public function setThoiGianAttribute($value)
    {
        if (!empty($value)) {
            if ($value instanceof Carbon) {
                $this->attributes['thoi_gian'] = $value;
            } else if (is_numeric($value)) {
                $this->attributes['thoi_gian'] = Carbon::createFromTimestamp(($value - 25569) * 86400)->startOfDay();
            } else {
                $this->attributes['thoi_gian'] = Carbon::createFromFormat(config('app.format_date'), $value)->startOfDay();
            }
        }
    }

    public function getThoiGianAttribute()
    {
        if (!empty($this->attributes['thoi_gian'])) {
            return Carbon::parse($this->attributes['thoi_gian'])->format(config('app.format_date'));
        } else {
            return null;
        }
    }

    public function ketQuaThanhTra(){
        return $this->belongsTo('App\KetQuaThanhTra','ket_qua_thanh_tra_id')->withDefault();
    }
}
