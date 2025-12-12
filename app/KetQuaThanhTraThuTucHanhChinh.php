<?php

namespace App;

use App\Helpers\UpperHelpers;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class KetQuaThanhTraThuTucHanhChinh extends Model
{
    protected $fillable = [
        'id',
        'so_ket_luan',
        'ngay_ket_luan_thanh_tra',
        'loai_thu_tuc_hanh_chinh_id',
        'so_quyet_dinh_phe_duyet',
        'co_quan_phe_duyet_id',
        'thoi_gian_phe_duyet',
        'note',
        'ket_qua_thanh_tra_chung_id',
        'ghi_chu'
    ];
    protected $date = ['thoi_gian_phe_duyet', 'ngay_ket_luan_thanh_tra', 'created_at', 'updated_at'];

    public function setNoteAttribute($value)
    {

        if (!empty($value)) {
            $this->attributes['note'] = UpperHelpers::upper($value);
        } else {
            $this->attributes['note'] = null;
        }
    }

    public function setNgayKetLuanThanhTraAttribute($value)
    {

        if (!empty($value)) {
            if ($value instanceof Carbon) {
                $this->attributes['ngay_ket_luan_thanh_tra'] = $value;
            } else if (is_numeric($value)) {
                $this->attributes['ngay_ket_luan_thanh_tra'] = Carbon::createFromTimestamp(($value - 25569) * 86400)->startOfDay();
            } else {

                $this->attributes['ngay_ket_luan_thanh_tra'] = Carbon::createFromFormat(config('app.format_date'), $value)->startOfDay();
            }
        }
    }
    public function getNgayKetLuanThanhTraAttribute()
    {
        if (!empty($this->attributes['ngay_ket_luan_thanh_tra'])) {
            return Carbon::parse($this->attributes['ngay_ket_luan_thanh_tra'])->format(config('app.format_date'));
        } else {
            return null;
        }
    }

    public function getThoiGianPheDuyetAttribute()
    {
        if (!empty($this->attributes['thoi_gian_phe_duyet'])) {
            return Carbon::parse($this->attributes['thoi_gian_phe_duyet'])->format(config('app.format_date'));
        } else {
            return null;
        }
    }

    public function setThoiGianPheDuyetAttribute($value)
    {
        if (!empty($value)) {
            if ($value instanceof Carbon) {
                $this->attributes['thoi_gian_phe_duyet'] = $value;
            } else if (is_numeric($value)) {
                $this->attributes['thoi_gian_phe_duyet'] = Carbon::createFromTimestamp(($value - 25569) * 86400)->startOfDay();
            } else {
                $this->attributes['thoi_gian_phe_duyet'] = Carbon::createFromFormat(config('app.format_date'), $value)->startOfDay();
            }
        }
    }

    public function coQuanToChuc()
    {
        return $this->belongsTo('App\CoQuanToChuc', 'co_quan_phe_duyet_id')->withDefault();
    }

    public function loaiThuTuc()
    {
        return $this->belongsTo('App\LoaiThuTucHanhChinh', 'loai_thu_tuc_hanh_chinh_id')->withDefault();
    }

    public function ketQuaThanhTraChung()
    {
        return $this->belongsTo('App\KetQuaThanhTraChung', 'ket_qua_thanh_tra_chung_id')->withDefault();
    }
}
