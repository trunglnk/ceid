<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhieuThuNghiems extends Model
{
    //
    protected $fillable = [
        'ten_khach_hang',
        'dia_diem_lay_mau',
        'dia_chi',
        'vi_tri_quan_trac',
        'kinh_do',
        'vi_do',
        'dac_diem',
        'ngay_lay_mau',
        'nguoi_lay_mau',
        'thoi_tiet',
        'ngay_phan_tich_start',
        'ngay_phan_tich_end',
        'nguoi_phan_tich',
    ];

    public function ketQuaThanhTraNuocThai()
    {
        return $this->hasMany(KetQuaThanhTraNuocThai::class, 'phieu_thu_nghiem_id');
    }
    public function ketQuaThanhTraKhiThai()
    {
        return $this->hasMany(KetQuaThanhTraKhiThai::class, 'phieu_thu_nghiem_id');
    }
    public function ketQuaThanhTraCtrsh()
    {
        return $this->hasMany(KetQuaThanhTraChatThaiRanSinhHoat::class, 'phieu_thu_nghiem_id');
    }
    public function ketQuaThanhTraCttrCntt()
    {
        return $this->hasMany(KetQuaThanhTraChatThaiRanCNTT::class, 'phieu_thu_nghiem_id');
    }
    public function ketQuaThanhTraCtnh()
    {
        return $this->hasMany(KetQuaThanhTraChatThaiNguyHai::class, 'phieu_thu_nghiem_id');
    }

    public function thuNghiem()
    {
        return $this->hasMany(ThuNghiems::class, 'phieu_thu_nghiem_id');
    }

    public function attachmentNuocThai()
    {
        return $this->hasOne(Attachment::class, 'reference_id')->where('reference_type', 'phieu_thu_nghiem_nuoc_thai');
    }

    public function attachmentKhiThai()
    {
        return $this->hasOne(Attachment::class, 'reference_id')->where('reference_type', 'phieu_thu_nghiem_khi_thai');
    }

    protected $appends = ['attachment'];

    public function getAttachmentAttribute()
    {
        if ($this->relationLoaded('attachmentNuocThai') && $this->attachmentNuocThai) {
            return $this->attachmentNuocThai;
        }
        if ($this->relationLoaded('attachmentKhiThai') && $this->attachmentKhiThai) {
            return $this->attachmentKhiThai;
        }
        return null;
    }
}
