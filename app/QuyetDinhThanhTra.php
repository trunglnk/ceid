<?php

namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class QuyetDinhThanhTra extends Model
{
    protected $fillable = [
        'id',
        'co_quan_quyet_dinh',
        'hinh_thuc_thanh_tra',
        'co_quan_thuc_hien',
        'nguoi_tao',
        'nguoi_cap_nhat',
        'so_quyet_dinh',
        'ngay_ra_quyet_dinh',
        'thoi_gian_thong_bao',
        'ten',
        'nam_ke_hoach',
        'ma_dinh_danh',
        'trang_thai_dong_bo_ve',
        'trang_thai_dong_bo_len',
        'thoi_gian_gui_len',
        'ten_co_quan_chu_tri',
        'thoi_gian_dong_bo'
    ];

    protected $dates = ['created_at', 'updated_at', 'ngay_ra_quyet_dinh', 'thoi_gian_thong_bao'];

    protected static function boot()
    {
        parent::boot();
    }

    public function setNgayRaQuyetDinhAttribute($value)
    {

        if (!empty($value)) {
            if ($value instanceof Carbon) {
                $this->attributes['ngay_ra_quyet_dinh'] = $value;
            } else if (is_numeric($value)) {
                $this->attributes['ngay_ra_quyet_dinh'] = Carbon::createFromTimestamp(($value - 25569) * 86400)->startOfDay();
            } else {
                $this->attributes['ngay_ra_quyet_dinh'] = Carbon::createFromFormat(config('app.format_date'), $value)->startOfDay();
            }
        }
    }
    public function getNgayRaQuyetDinhAttribute()
    {
        if (!empty($this->attributes['ngay_ra_quyet_dinh'])) {
            return Carbon::parse($this->attributes['ngay_ra_quyet_dinh'])->format(config('app.format_date'));
        } else {
            return null;
        }
    }
    public function setThoiGianThongBaoAttribute($value)
    {

        if (!empty($value)) {
            if ($value instanceof Carbon) {
                $this->attributes['thoi_gian_thong_bao'] = $value;
            } else if (is_numeric($value)) {
                $this->attributes['thoi_gian_thong_bao'] = Carbon::createFromTimestamp(($value - 25569) * 86400)->startOfDay();
            } else {
                $this->attributes['thoi_gian_thong_bao'] = Carbon::createFromFormat(config('app.format_date'), $value)->startOfDay();
            }
        }
    }
    public function getThoiGianThongBaoAttribute()
    {
        if (!empty($this->attributes['thoi_gian_thong_bao'])) {
            return Carbon::parse($this->attributes['thoi_gian_thong_bao'])->format(config('app.format_date'));
        } else {
            return null;
        }
    }
    public function hinhThucThanhTra()
    {
        return $this->belongsTo('App\CheDoThanhTra', 'hinh_thuc_thanh_tra')->withDefault();
    }
    public function coQuanQuyetDinh()
    {
        return $this->belongsTo('App\CoQuanToChuc', 'co_quan_quyet_dinh')->withDefault();
    }
    public function cheDoThanhTra()
    {
        return $this->belongsTo('App\CheDoThanhTra', 'hinh_thuc_thanh_tra')->withDefault();
    }
    public function coQuanThucHien()
    {
        return $this->belongsTo('App\CoQuanToChuc', 'co_quan_thuc_hien')->withDefault();
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment', 'reference_id')->where('reference_type', 'quyet_dinh_thanh_tra');
    }
    public function nguoiCapNhat()
    {
        return $this->belongsTo('App\User', 'nguoi_cap_nhat')->withDefault();
    }
    public function nguoiTao()
    {
        return $this->belongsTo('App\User', 'nguoi_tao')->withDefault();
    }
    public function ketQuaThanhTras()
    {
        return $this->hasMany('App\KetQuaThanhTra', 'quyet_dinh_thanh_tra_id');
    }
}
