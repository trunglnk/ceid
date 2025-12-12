<?php

namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class KetQuaThanhTraKhiThai extends Model
{
    protected $fillable = [
        'luu_luong',
        'thanh_phan',
        'cong_suat_he_thong_xu_ly',
        'nguon_tiep_nhan',
        'thong_so_khi_thai_vuot_chuan',
        'quy_trinh_xu_ly',
        'so_ket_luan',
        'ngay_ket_luan_thanh_tra',
        'ket_qua_thanh_tra_chung_id',
        'phieu_thu_nghiem_id',
        'loai_hinh_quan_trac'
    ];

    protected $dates = ['ngay_ket_luan_thanh_tra', 'created_at', 'updated_at'];

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

    public function setLuuLuongAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['luu_luong'] = 0;
        } else {
            $this->attributes['luu_luong'] = str_replace(',', '', $value);
        }
    }

    public function setCongSuatHeThongXuLyAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['cong_suat_he_thong_xu_ly'] = 0;
        } else {
            $this->attributes['cong_suat_he_thong_xu_ly'] = str_replace(',', '', $value);
        }
    }

    public function setThongSoKhiThaiVuotChuanAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['thong_so_khi_thai_vuot_chuan'] = 0;
        } else {
            $this->attributes['thong_so_khi_thai_vuot_chuan'] = str_replace(',', '', $value);
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
