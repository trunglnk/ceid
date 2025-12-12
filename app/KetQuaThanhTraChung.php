<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KetQuaThanhTraChung extends Model
{
    protected $fillable = [
        'id',
        'ket_qua_thanh_tra_id',
        'ten',
        'kinh_do',
        'vi_do',
        'dia_chi_lien_he',
        'khu_cong_nghiep_id',
        'xa_phuong',
        'quan_huyen_id',
        'tinh_thanh_id',
        'dien_tich',
        'so_nguoi_lao_dong',
        'so_giay_chung_nhan_dau_tu',
        'ngay_cap_giay_chung_nhan_dau_tu',
        'co_quan_cap_giay_chung_nhan_dau_tu',
        'nguyen_lieu_chinh_su_dung',
        'nhien_lieu_chinh_su_dung',
        'hoa_chat_su_dung',
        'nguon_nuoc_su_dung',
        'vung_kinh_te_trong_diem_id',
        'mien_id',
        'luu_vuc_song_id',
        'ngay_cap_giay_chung_nhan_kinh_doanh',
        'so_giay_chung_nhan_kinh_doanh',
        'co_quan_cap_giay_chung_nhan_kinh_doanh',
        'luong_nuoc_su_dung',
        'dia_chi_hoat_dong',
        'co_so_san_xuat_id',
        'ty_le_lap_day',
        'tong_dien_tich',
        'chuyen_doi_don_vi_id',
        'dien_tich_dat_cn',
        'dien_tich_dat_cho_thue',
        'dien_tich_dat_cay_xanh'
    ];

    public $timestamps = false;

    public function ketQuaThanhTra()
    {
        return $this->belongsTo('App\KetQuaThanhTra', 'ket_qua_thanh_tra_id')->withDefault();
    }

    public function coSoSanXuat()
    {
        return $this->belongsTo('App\CoSoSanXuat', 'co_so_san_xuat_id')->withDefault();
    }

    public function khuCongNghiep()
    {
        return $this->belongsTo('App\KhuCongNghiep')->withDefault();
    }
    public function quanHuyen()
    {
        return $this->belongsTo('App\QuanHuyen')->withDefault();
    }
    public function tinhThanh()
    {
        return $this->belongsTo('App\TinhThanh')->withDefault();
    }

    public function setNgayCapGiayChungNhanDauTuAttribute($value)
    {
        if (!empty($value)) {
            if ($value instanceof Carbon) {
                $this->attributes['ngay_cap_giay_chung_nhan_dau_tu'] = $value;
            } else if (is_numeric($value)) {
                $this->attributes['ngay_cap_giay_chung_nhan_dau_tu'] = Carbon::createFromTimestamp(($value - 25569) * 86400)->startOfMonth();
            } else {

                $this->attributes['ngay_cap_giay_chung_nhan_dau_tu'] = Carbon::createFromFormat(config('app.format_date'), $value);
            }
        } else {
            $this->attributes['ngay_cap_giay_chung_nhan_dau_tu'] = null;
        }
    }

    public function getNgayCapGiayChungNhanDauTuAttribute()
    {
        if (!empty($this->attributes['ngay_cap_giay_chung_nhan_dau_tu'])) {
            return Carbon::parse($this->attributes['ngay_cap_giay_chung_nhan_dau_tu'])->format(config('app.format_date'));
        } else {
            return null;
        }
    }

    public function getNgayCapGiayChungNhanKinhDoanhAttribute()
    {
        if (!empty($this->attributes['ngay_cap_giay_chung_nhan_kinh_doanh'])) {
            return Carbon::parse($this->attributes['ngay_cap_giay_chung_nhan_kinh_doanh'])->format(config('app.format_date'));
        } else {
            return null;
        }
    }

    public function setNgayCapGiayChungNhanKinhDoanhAttribute($value)
    {
        if (!empty($value)) {
            if ($value instanceof Carbon) {
                $this->attributes['ngay_cap_giay_chung_nhan_kinh_doanh'] = $value;
            } else if (is_numeric($value)) {
                $this->attributes['ngay_cap_giay_chung_nhan_kinh_doanh'] = Carbon::createFromTimestamp(($value - 25569) * 86400)->startOfMonth();
            } else {

                $this->attributes['ngay_cap_giay_chung_nhan_kinh_doanh'] = Carbon::createFromFormat(config('app.format_date'), $value);
            }
        } else {
            $this->attributes['ngay_cap_giay_chung_nhan_kinh_doanh'] = null;
        }
    }

    public function ketQuaThanhTraChungLoaiHinhHoatDongs()
    {
        return $this->hasMany('App\KetQuaThanhTraChungLoaiHinhHoatDong', 'ket_qua_thanh_tra_chung_id');
    }

    public function ketQuaThanhTraThuTucHanhChinhs()
    {
        return $this->hasMany('App\KetQuaThanhTraThuTucHanhChinh', 'ket_qua_thanh_tra_chung_id');
    }

    public function ketQuaThanhTraNuocThais()
    {
        return $this->hasMany('App\KetQuaThanhTraNuocThai', 'ket_qua_thanh_tra_chung_id');
    }

    public function ketQuaThanhTraKhiThais()
    {
        return $this->hasMany('App\KetQuaThanhTraKhiThai', 'ket_qua_thanh_tra_chung_id');
    }

    public function ketQuaThanhTraChatThaiRanSinhHoats()
    {
        return $this->hasMany('App\KetQuaThanhTraChatThaiRanSinhHoat', 'ket_qua_thanh_tra_chung_id');
    }
    public function ketQuaThanhTraChatThaiRanCNTT()
    {
        return $this->hasMany('App\KetQuaThanhTraChatThaiRanCNTT', 'ket_qua_thanh_tra_chung_id');
    }
    public function ketQuaThanhTraChatThaiNguyHai()
    {
        return $this->hasMany('App\KetQuaThanhTraChatThaiNguyHai', 'ket_qua_thanh_tra_chung_id');
    }

    public function ketQuaPhanTichMoiTruong()
    {
        return $this->hasMany('App\KetQuaPhanTichMoiTruong', 'ket_qua_thanh_tra_chung_id');
    }

    public function coQuanDauTu()
    {
        return $this->belongsTo('App\CoQuanToChuc', 'co_quan_cap_giay_chung_nhan_dau_tu')->withDefault();
    }

    public function coQuanKinhDoanh()
    {
        return $this->belongsTo('App\CoQuanToChuc', 'co_quan_cap_giay_chung_nhan_kinh_doanh')->withDefault();
    }

    public function chuyenDoiDonVi()
    {
        return $this->belongsTo(ChuyenDoiDonVi::class, 'chuyen_doi_don_vi_id');
    }

    public function ketQuaQuanTrac()
    {
        return $this->hasMany('App\KetQuaQuanTrac', 'ket_qua_thanh_tra_chung_id');
    }
}
