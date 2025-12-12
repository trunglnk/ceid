<?php

namespace App;

use App\Helpers\UpperHelpers;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KetQuaThanhTraChatThaiNguyHai extends Model
{
    protected $fillable = [
        'khoi_luong_phat_sinh_thuc_te',
        'khoi_luong_phat_sinh_theo_so_dang_ki',
        'thanh_phan_chinh',
        'tu_xu_ly',
        'thue_xu_ly',
        'co_quan_thue_xu_ly',
        'so_ket_luan',
        'ngay_ket_luan_thanh_tra',
        'ket_qua_thanh_tra_chung_id',
        'phieu_thu_nghiem_id'
    ];

    public function setThanhPhanChinhAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['thanh_phan_chinh'] = UpperHelpers::upper($value);
        } else {
            $this->attributes['thanh_phan_chinh'] = null;
        }
    }

    public function setCoQuanThueXuLyAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['co_quan_thue_xu_ly'] = UpperHelpers::upper($value);
        } else {
            $this->attributes['co_quan_thue_xu_ly'] = null;
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
        } else {
            $this->attributes['ngay_ket_luan_thanh_tra'] = null;
        }
    }

    public function setKhoiLuongPhatSinhThucTeAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['khoi_luong_phat_sinh_thuc_te'] = 0;
        } else {
            $this->attributes['khoi_luong_phat_sinh_thuc_te'] = str_replace(',', '', $value);
        }
    }

    public function setKhoiLuongPhatSinhTheoSoDangKiAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['khoi_luong_phat_sinh_theo_so_dang_ki'] = 0;
        } else {
            $this->attributes['khoi_luong_phat_sinh_theo_so_dang_ki'] = str_replace(',', '', $value);
        }
    }

    public function ketQuaThanhTraChung()
    {
        return $this->belongsTo('App\KetQuaThanhTraChung', 'ket_qua_thanh_tra_chung_id')->withDefault();
    }

    public function phieuThuNghiem()
    {
        return $this->belongsTo(PhieuThuNghiems::class, 'phieu_thu_nghiem_id');
    }
}
