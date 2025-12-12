<?php

namespace App;

use App\Helpers\UpperHelpers;
use App\Traits\ExecuteString;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CoSoSanXuat extends Model
{
    use ExecuteString;
    protected $fillable = [
        'id',
        'ma',
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
        'cong_suat_thiet_ke',
        'ngay_cap_giay_chung_nhan_dau_tu',
        'co_quan_cap_giay_chung_nhan_dau_tu',
        'nguon_nuoc_su_dung',
        'luong_nuoc_su_dung',
        'hoa_chat_su_dung',
        'nhien_lieu_chinh_su_dung',
        'nguyen_lieu_chinh_su_dung',
        'luu_vuc_song_id',
        'vung_kinh_te_trong_diem_id',
        'mien_id',
        'created_by',
        'updated_by',
        'ngay_cap_giay_chung_nhan_kinh_doanh',
        'co_quan_cap_giay_chung_nhan_kinh_doanh',
        'so_giay_chung_nhan_kinh_doanh',
        'so_giay_chung_nhan_dau_tu',
        'dia_chi_hoat_dong',
        'organization_id',
        'loai_co_so_id',
        // 'ngay_tao',
        // 'ngay_cap_nhat',
        // 'loai_hinh_oi_nhiem_id',
        // 'loai_nganh_kinh_te_id',
        'ma_dinh_danh',
        'trang_thai_dong_bo',
        'loai_du_lieu',
        'loai_khu_cong_nghiep',
        'ngay_ket_luan',
        'ty_le_lap_day',
        'tong_dien_tich',
        'chuyen_doi_don_vi_id',
        'dien_tich_dat_cn',
        'dien_tich_dat_cho_thue',
        'dien_tich_dat_cay_xanh'
    ];
    protected $dates = ['ngay_cap_giay_chung_nhan_kinh_doanh', 'ngay_cap_giay_chung_nhan_dau_tu', 'created_at', 'updated_at', 'image_cover'];

    protected $appends = ['old_name'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new \App\Scopes\UserScope);
    }

    public function setTenAttribute($value)
    {

        if (!empty($value)) {
            $this->attributes['ten'] = UpperHelpers::upper($value);
        } else {
            $this->attributes['ten'] = null;
        }
    }

    public function setDiaChiLienHeAttribute($value)
    {

        if (!empty($value)) {
            $this->attributes['dia_chi_lien_he'] = UpperHelpers::upper($value);
        } else {
            $this->attributes['dia_chi_lien_he'] = null;
        }
    }

    public function setDiaChiHoatDongAttribute($value)
    {

        if (!empty($value)) {
            $this->attributes['dia_chi_hoat_dong'] = UpperHelpers::upper($value);
        } else {
            $this->attributes['dia_chi_hoat_dong'] = null;
        }
    }
    public function setNguyenLieuChinhSuDungAttribute($value)
    {

        if (!empty($value)) {
            $this->attributes['nguyen_lieu_chinh_su_dung'] = UpperHelpers::upper($value);
        } else {
            $this->attributes['nguyen_lieu_chinh_su_dung'] = null;
        }
    }
    public function setNhienLieuChinhSuDungAttribute($value)
    {

        if (!empty($value)) {
            $this->attributes['nhien_lieu_chinh_su_dung'] = UpperHelpers::upper($value);
        } else {
            $this->attributes['nhien_lieu_chinh_su_dung'] = null;
        }
    }
    public function setHoaChatSuDungAttribute($value)
    {

        if (!empty($value)) {
            $this->attributes['hoa_chat_su_dung'] = UpperHelpers::upper($value);
        } else {
            $this->attributes['hoa_chat_su_dung'] = null;
        }
    }
    public function setNguonNuocSuDungAttribute($value)
    {

        if (!empty($value)) {
            $this->attributes['nguon_nuoc_su_dung'] = UpperHelpers::upper($value);
        } else {
            $this->attributes['nguon_nuoc_su_dung'] = null;
        }
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

    public function getOldNameAttribute($value)
    {
        if (empty($this->attributes['ten'])) {
            return [];
        }
        $data = [];
        $ket_luan_thanh_tra_chungs = KetQuaThanhTraChung::where('co_so_san_xuat_id', $this->attributes['id'])
            ->where('ten', '!=', $this->attributes['ten'])
            // ->whereRaw('upper("ten") not like ?', [mb_strtoupper($this->attributes['ten'])])
            ->whereHas('ketQuaThanhTra')
            ->with('ketQuaThanhTra')
            ->get(['ten', 'id', 'ket_qua_thanh_tra_id']);

        foreach ($ket_luan_thanh_tra_chungs->unique('ten') as $ket_luan_thanh_tra_chung) {
            $data[] = Carbon::createFromFormat(config('app.format_date'), $ket_luan_thanh_tra_chung->ketQuaThanhTra->ngay_thanh_tra)->year . '-' . $ket_luan_thanh_tra_chung['ten'];
        }

        return $data;
    }
    public function loaiHinhONhiem()
    {
        return $this->belongsToMany('App\LoaiHinhOiNhiem', 'co_so_san_xuat_loai_hinh_o_nhiems', 'co_so_san_xuat_id', 'loai_hinh_o_nhiem_id');
    }
    public function loaiNganhNghe()
    {
        return $this->belongsToMany('App\LoaiHinhCoSo', 'co_so_san_xuat_loai_nganh_nghes', 'co_so_id', 'loai_nganh_nghe_id');
    }
    public function khuCongNghiep()
    {
        return $this->belongsTo('App\KhuCongNghiep', 'khu_cong_nghiep_id')->withDefault();
    }

    public function tinhThanh()
    {
        return $this->belongsTo('App\TinhThanh', 'tinh_thanh_id')->withDefault();
    }

    public function quanHuyen()
    {
        return $this->belongsTo('App\QuanHuyen', 'quan_huyen_id')->withDefault();
    }

    public function luuVucSong()
    {
        return $this->belongsTo('App\LuuVucSong', 'luu_vuc_song_id')->withDefault();
    }

    public function vungKinhTeTrongDiem()
    {
        return $this->belongsTo('App\VungKinhTeTrongDiem', 'vung_kinh_te_trong_diem_id')->withDefault();
    }

    public function mien()
    {
        return $this->belongsTo('App\Mien', 'mien_id')->withDefault();
    }

    public function coQuanDauTu()
    {
        return $this->belongsTo('App\CoQuanToChuc', 'co_quan_cap_giay_chung_nhan_dau_tu')->withDefault();
    }

    public function coQuanKinhDoanh()
    {
        return $this->belongsTo('App\CoQuanToChuc', 'co_quan_cap_giay_chung_nhan_kinh_doanh')->withDefault();
    }

    public function organization()
    {
        return $this->belongsTo('App\Organization', 'organization_id')->orderBy('id');
    }

    public function chiTietCongSuat()
    {
        return $this->hasMany('App\ChiTietCongSuat', 'co_so_san_xuat_id')->orderBy('id');
    }

    public function getImageCoverAttribute()
    {
        return url('/') . '/images/cososanxuat/co-so-san-xuat-' . rand(0, 10);
    }

    public function ketQuaThanhTraChungs()
    {
        return $this->hasMany('App\KetQuaThanhTraChung', 'co_so_san_xuat_id')->orderBy('id');
    }

    public function loaiCoSo()
    {
        return $this->belongsTo('App\LoaiCoSo', 'loai_co_so_id')->withDefault();
    }
    public function loaiKhuCongNghiep()
    {
        return $this->belongsTo('App\LoaiKhuCongNghiep', 'loai_khu_cong_nghiep', 'ma')->withDefault();
    }


    public function tinhThanhs()
    {
        return $this->belongsToMany('App\TinhThanh', 'dia_diem_co_so_san_xuats', 'co_so_san_xuat_id', 'tinh_thanh_id');
    }
    public function quanHuyens()
    {
        return $this->belongsToMany('App\QuanHuyen', 'dia_diem_co_so_san_xuats', 'co_so_san_xuat_id', 'quan_huyen_id');
    }
    public function phuongXas()
    {
        return $this->belongsToMany('App\PhuongXa', 'dia_diem_co_so_san_xuats', 'co_so_san_xuat_id', 'phuong_xa_id');
    }

    public function getTinhThanhIdTextAttribute()
    {
        $v = $this->attributes['tinh_thanh_id'] ?? null;
        return is_null($v) ? null : (string) $v;
    }

    public function diemTramQtmtTheoTinh()
    {
        return $this->hasMany(DiemTramQTMT::class, 'tinh_thanh_ma', 'tinh_thanh_id_text');
    }

    public function chuyenDoiDonVi()
    {
        return $this->belongsTo(ChuyenDoiDonVi::class, 'chuyen_doi_don_vi_id');
    }

    public function setNgayKetLuanAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['ngay_ket_luan'] = null;
            return;
        }

        if ($value instanceof Carbon) {
            $this->attributes['ngay_ket_luan'] = $value;
            return;
        }

        // Nếu là timestamp (Excel hoặc số)
        if (is_numeric($value)) {
            $this->attributes['ngay_ket_luan'] = Carbon::createFromTimestamp(($value - 25569) * 86400)->format('Y-m-d');
            return;
        }

        // Nếu là chuỗi kiểu d/m/Y
        try {
            $this->attributes['ngay_ket_luan'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        } catch (\Exception $e) {
            $this->attributes['ngay_ket_luan'] = null;
        }
    }

    public function getNgayKetLuanAttribute()
    {
        if (!empty($this->attributes['ngay_ket_luan'])) {
            return Carbon::parse($this->attributes['ngay_ket_luan'])->format('d/m/Y');
        }
        return null;
    }
}
