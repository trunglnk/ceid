<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NhomHanhViViPham extends Model
{
    protected $appends = ['loai_hinh_quan_trac'];
    protected $fillable = [
        'id',
        'vi_pham',
        'vi_pham_xa_thai',
        'vi_pham_thu_tuc_hanh_chinh',
        'ket_qua_thanh_tra_id',
        'dtmdakhbvmt_thuc_hien_khong_dung_noi_dung',
        'dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung',
        'co_ket_hoach_quan_ly_moi_truong',
        'khong_xay_lap',
        'xay_lap_khong_dung',
        'khong_co_giay_xac_nhan_hoan_thanh',
        'khong_thuc_hien_giay_xac_nhan',
        'van_hanh_khong_dung_khong_thuong_xuyen',
        'gsmt_thuc_hien',
        'gsmt_khong_thuc_hien',
        'gsmt_thuc_hien_khong_dung_khong_du',
        'thuc_hien_sai_giay_xac_nhan',
        'khong_co_giay_phep_xa_thai',
        'nuoc_thai_vuot',
        'co_he_thong_thu_gom_nuoc_thai_rieng_biet',
        'nuoc_thai_vuot_qcvn_tu',
        'nuoc_thai_vuot_qcvn_toi',
        'co_bien_phap_xu_ly_khi_thai',
        'khi_thai_vuot_qcvn_tu',
        'khi_thai_vuot_qcvn_toi',
        'ctrsh_co_thu_gom',
        'ctrsh_co_phan_loai',
        'ctrsh_co_luu_tru',
        'ctrsh_co_chuyen_giao',
        'ctrcn_co_thu_gom',
        'ctrcn_co_phan_loai',
        'ctrcn_co_luu_tru',
        'ctrcn_co_chuyen_giao',
        'ctrnh_vi_pham_chung_tu',
        'ctrnh_co_thu_gom',
        'ctrnh_co_phan_loai',
        'ctrnh_co_luu_tru',
        'ctrnh_co_de_lan',
        'ctrnh_co_chuyen_giao',
        'ctrnh_co_chon_lap',
        'ctrnh_co_do_thai',
        'ctrnh_co_giay_phep',
        'nhom_hanh_vi_khac',
        'vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong',
        'vi_pham_cac_quy_dinh_bao_ve_moi_truong',
        'co_hanh_vi_can_tro_ve_bvmt',
        'khong_co_bao_cao_dtm',
        'thong_so_vuot_chuan',
        'khi_thai_vuot',
        'khi_thai_muc_vi_pham_id',
        'khi_thai_khoan_vi_pham_id',
        'nuoc_thai_muc_vi_pham_id',
        'nuoc_thai_khoan_vi_pham_id',
        'ma_loai_hinh_quan_trac',

    ];

    public function viPhamXaThaiKhiThais()
    {
        return $this->hasMany(ChiTietViPhamXaThai::class, 'nhom_hanh_vi_vi_pham_id')->where('type', 'khi_thai');
    }
    public function viPhamXaThaiNuocThais()
    {
        return $this->hasMany(ChiTietViPhamXaThai::class, 'nhom_hanh_vi_vi_pham_id')->where('type', 'nuoc_thai');
    }

    public function chiTietViPhamXaThais()
    {
        return $this->hasMany(ChiTietViPhamXaThai::class, 'nhom_hanh_vi_vi_pham_id');
    }

    public function getLoaiHinhQuanTracAttribute()
    {
        if ($this->attributes['ma_loai_hinh_quan_trac'] == 'dinh_ky') {
            return ['id' => $this->attributes['ma_loai_hinh_quan_trac'], 'ten' => 'Định kỳ'];
        } else if ($this->attributes['ma_loai_hinh_quan_trac'] == 'tu_dong') {
            return ['id' => $this->attributes['ma_loai_hinh_quan_trac'], 'ten' => 'Tự động'];
        } else {
            return null;
        }
    }
    public $timestamps = false;
}
